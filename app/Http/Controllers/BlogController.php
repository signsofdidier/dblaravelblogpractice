<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    protected $folder = 'blogs';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $blogs = Blog::withTrashed()->with(['photo','user','categories'])->orderByDesc('id')->paginate(5);
        return view('backend.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::pluck('name', 'id')->all();
        return view('backend.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $messages = [
            'title.required' => 'De titel is verplicht.',
            'category_id.required' => 'Selecteer minimaal één category.',
            'category_id.array' => 'De categorien moeten een lijst van ID\'s zijn.',
            'category_id.*.exists' => 'Een van de geselecteerde categorien bestaat niet.',
            'description.required' => 'De beschrijving is verplicht.',
            'photo_id.image' => 'De geüploade afbeelding moet een geldig afbeeldingsbestand zijn',

        ];
        //Valideer de request gegevens
        $validatedData = $request->validate([
           'title' => 'required|string|max:255',
            'category_id' => 'required|array', // Moet nu een array zijn
            'category_id.*' => 'exists:categories,id', // Elke category in de array moet geldig zijn
           'description' => 'required|string',
           'photo_id' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ], $messages);


        //foto in fototabel

        if ($request->hasFile('photo_id')) {
            $file = $request->file('photo_id');
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $filePath = $this->folder . '/' . $uniqueName;
            $file->storeAs('', $filePath, 'public');
            $photo = Photo::create([
                'path' => $filePath,
                'alternate_text' => $validatedData['title']
            ]);
            $validatedData['photo_id'] = $photo->id;
        }

        //auteur toevoegen
        $validatedData['user_id']=Auth::user()->id;

        $blog = Blog::create(
            [
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'photo_id' => $validatedData['photo_id'] ?? null,
                'user_id' => $validatedData['user_id'],
            ]
        );

        //Koppel de blog aan de geselecteerde categories
        $blog->categories()->sync($validatedData['category_id']);

        // Redirect naar een overzicht of geef een bericht weer
        return redirect()->route('blogs.index')->with('message', "Blog created successfully!");
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
    public function edit(string $id)
    {
        //
        $blog = Blog::with('categories', 'photo')->findOrFail($id);
        $categories = Category::pluck('name', 'id')->all();

        $photoDetails = [
            'exists' => false,
            'filesize' => 0,
            'width' => 'N/A',
            'height' => 'N/A',
            'extension' => ''
        ];

        // Controleer of er een foto is en of deze bestaat op de 'public' disk
        if ($blog->photo && Storage::disk('public')->exists($blog->photo->path)) {
            $photoDetails['exists'] = true;
            $photoDetails['filesize'] = round(Storage::disk('public')->size($blog->photo->path) /
                1024, 2);
            $photoPath = Storage::disk('public')->path($blog->photo->path);
            $dimensions = getimagesize($photoPath);
            $photoDetails['width'] = $dimensions[0] ?? 'N/A';
            $photoDetails['height'] = $dimensions[1] ?? 'N/A';
            $photoDetails['extension'] = \Illuminate\Support\Str::upper(pathinfo($blog->photo->path, PATHINFO_EXTENSION));
        }

        return view('backend.blogs.edit', compact('blog','categories', 'photoDetails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Haal de gebruiker op of geef een 404 als deze niet bestaat
        $blog = Blog::findOrFail($id);

        // Validatieberichten
        $messages = [
            'title.required' => 'De titel is verplicht.',
            'category_id.required' => 'Selecteer minimaal één category.',
            'category_id.array' => 'De categories moeten een lijst van ID\'s zijn.',
            'category_id.*.exists' => 'Een van de geselecteerde categories bestaat niet.',
            'description.required' => 'De beschrijving is verplicht.',
            'photo_id.image' => 'De geüploade afbeelding moet een geldig afbeeldingsbestand zijn.',
            'photo_id.mimes' => 'De afbeelding moet een bestand van het type: jpg, jpeg, png, gif zijn.',
            'photo_id.max' => 'De afbeelding mag maximaal :max kilobytes zijn.',
        ];
        // Valideer de input; de e-mail validatie houdt rekening met de huidige gebruiker
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|array',
            'category_id.*' => 'exists:categories,id',
            'description' => 'required|string',
            'photo_id' => 'nullable|image|mimes:jpg,jpeg,png,gif',
        ], $messages);

        // Verwerk de foto: als er een nieuwe foto is geüpload
        if ($request->hasFile('photo_id')) {
            $file = $request->file('photo_id');
            // Genereer een unieke bestandsnaam met behulp van een UUID
            $uniqueName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            // Gebruik de class-property (bijv. 'users') en bouw het bestandspad op als 'users/uniquename.ext'
            $filePath = $this->folder . '/' . $uniqueName;
            // Sla het bestand op in de 'public'-disk (die verwijst naar storage/app/public)
            $file->storeAs('', $filePath, 'public');
            // Controleer of de gebruiker al een foto-record heeft
            if ($blog->photo && Storage::disk('public')->exists($blog->photo->path)) {
                // Verwijder de oude fysieke foto
                Storage::disk('public')->delete($blog->photo->path);
                // Update het bestaande Photo-record met de nieuwe bestandsnaam en alternate text
                $blog->photo->update([
                    'path' => $filePath,
                    'alternate_text' => $validatedData['title']
                ]);
                // Gebruik hetzelfde photo record id
                $validatedData['photo_id'] = $blog->photo->id;
            } else {
                // Als er nog geen foto-record is, maak er dan een nieuw aan
                $photo = Photo::create([
                    'path' => $filePath,
                    'alternate_text' => $validatedData['title']
                ]);
                $validatedData['photo_id'] = $photo->id;
            }
        }

        // Werk de gebruiker bij met de gevalideerde data
        $blog->update($validatedData);

        // Synchroniseer de rollen voor de gebruiker
        $blog->categories()->sync($validatedData['category_id']);

        return redirect()->back()->with('message', 'Blog updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Haal de gebruiker op inclusief de photo-relatie
        $blog = Blog::with('photo')->findOrFail($id);
        // Controleer of de gebruiker een photo heeft
        if ($blog->photo) {
        // Controleer of het fysieke bestand bestaat op de 'public'-disk
            if (Storage::disk('public')->exists($blog->photo->path)) {
                Storage::disk('public')->delete($blog->photo->path);
            }
            // Verwijder het Photo-record uit de database
            $blog->photo()->delete();
        }

        // Verwijder de gebruiker (de pivot-records worden via cascades of model events afgehandeld)
        $blog->delete();

        // Redirect terug met een succesmelding
        return redirect()->route('blogs.index')->with('message', 'Blog and associated photo deleted successfully');
    }

    public function restore($id)
    {
        $blog = Blog::withTrashed()->findOrFail($id);
        $blog->restore();
        return redirect()->back()->with('message', 'Blog restored successfully');
    }
}
