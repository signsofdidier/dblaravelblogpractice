<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Photo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $folder = 'users';

    public function index()
    {
        //
        $users = User::withTrashed()->with('roles', 'photo')->orderBy('id', 'desc')->paginate(7);
        return view('backend.users.index', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Verkrijg de gevalideerde data (de validatieregels en -berichten staan nu in de FormRequest)
        $validatedData = $request->validated();

        // Hash het wachtwoord voordat je opslaat
        $validatedData['password'] = Hash::make($validatedData['password']);

        //Controleer of er een foto is geüpload en sla deze op
        if ($request->hasFile('photo_id')) {
            $file = $request->file('photo_id');
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $this->folder . '/' . $uniqueName;

            // Sla het bestand op in de 'public'-disk onder het subpad
            $file->storeAs('', $filePath, 'public');
            $photo = Photo::create([
                'path' => $filePath,
                'alternate_text' => $validatedData['name']
            ]);
            $validatedData['photo_id'] = $photo->id;
        }

        //maak de gebruiker aan ZONDER role id
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'is_active' => $validatedData['is_active'],
            'password' => $validatedData['password'],
            'photo_id' => $validatedData['photo_id'] ?? null,
        ]);

        //Koppel de gebruiker aan de geselecteerde rollen
        $user->roles()->sync($validatedData['role_id']);

        // Redirect naar een overzicht of geef een bericht weer
        return redirect()->route('users.index')->with('message', 'User created successfully');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $roles = Role::pluck('name', 'id')->all();
        return view('backend.users.create', compact('roles'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Zorg dat de relaties geladen zijn (eager loading)
        $user->load('roles', 'photo');

        // Haal de beschikbare rollen op voor de select-optie
        $roles = Role::pluck('name', 'id')->all();

        $photoDetails = [
            'exists' => false,
            'filesize' => 0,
            'width' => 'N/A',
            'height' => 'N/A',
            'extension' => '',
        ];

        // Controleer of er een foto is en of deze bestaat op de 'public' disk
        if ($user->photo && Storage::disk('public')->exists($user->photo->path)) {
            $photoDetails['exists'] = true;
            $photoDetails['filesize'] = round(Storage::disk('public')->size($user->photo->path) /
                1024, 2);
            $photoPath = Storage::disk('public')->path($user->photo->path);
            $dimensions = getimagesize($photoPath);
            $photoDetails['width'] = $dimensions[0] ?? 'N/A';
            $photoDetails['height'] = $dimensions[1] ?? 'N/A';
            $photoDetails['extension'] =
                Str::upper(pathinfo($user->photo->path, PATHINFO_EXTENSION));
        }

        return view('backend.users.edit', compact('user', 'roles', 'photoDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Validatieberichten
        $validatedData = $request->validated();

        // Laad de photo-relatie als deze nog niet geladen is
        $user->load('photo');

        // Verwerk het wachtwoord: als er een nieuw wachtwoord is ingevuld, hash deze; anders laat je de oude waarde intact.
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        // Verwerk de foto: als er een nieuwe foto is geüpload
        if ($request->hasFile('photo_id')) {
            $file = $request->file('photo_id');

            // Genereer een unieke bestandsnaam met behulp van een UUID
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Gebruik de class-property (bijv. 'users') en bouw het bestandspad op als 'users/uniquenam.ext'
            $filePath = $this->folder . '/' . $uniqueName;

            // Sla het bestand op in de 'public'-disk (die verwijst naar storage/app/public)
            $file->storeAs('', $filePath, 'public');

            // Controleer of de gebruiker al een foto-record heeft
            if ($user->photo && Storage::disk('public')->exists($user->photo->path)) {

                // Verwijder de oude fysieke foto
                Storage::disk('public')->delete($user->photo->path);

                // Update het bestaande Photo-record met de nieuwe bestandsnaam en alternate text
                $user->photo->update([
                    'path'          => $filePath,
                    'alternate_text' => $validatedData['name']
                ]);

                // Gebruik hetzelfde photo record id
                $validatedData['photo_id'] = $user->photo->id;
            } else {
                // Als er nog geen foto-record is, maak er dan een nieuw aan
                $photo = Photo::create([
                    'path'          => $filePath,
                    'alternate_text' => $validatedData['name']
                ]);
                $validatedData['photo_id'] = $photo->id;
            }
        }
        // Werk de gebruiker bij met de gevalideerde data
        $user->update($validatedData);

        // Synchroniseer de rollen voor de gebruiker
        $user->roles()->sync($validatedData['role_id']);
        return redirect()->back()->with('message', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Haal de gebruiker op inclusief de photo-relatie
        $user = User::with('photo')->findOrFail($id);

        // Controleer of de gebruiker een photo heeft
        if ($user->photo) {
        // Controleer of het fysieke bestand bestaat op de 'public'-disk
            if (Storage::disk('public')->exists($user->photo->path)) {
                Storage::disk('public')->delete($user->photo->path);
            }
        // Verwijder het Photo-record uit de database
            $user->photo()->delete();
        }
        // Verwijder de gebruiker (de pivot-records worden via cascades of model events afgehandeld)
        $user->delete();

        // Redirect terug met een succesmelding
        return redirect()->route('users.index')->with('message', 'User deleted successfully!');

    }

    public function restore($id){
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect()->back()->with('message', 'User restored successfully!');
    }
}
