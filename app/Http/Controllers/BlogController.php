<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $blogs = Blog::with(['photo','user'])->orderByDesc('id')->get();
        return view('backend.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('backend.blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $messages = [
            'title.required' => 'De titel is verplicht.',
            'description.required' => 'De beschrijving is verplicht.',
            'photo_id.image' => 'De geüploade afbeelding moet een geldig afbeeldingsbestand zijn',
        ];
        //Valideer de request gegevens
        $validatedData = $request->validate([
           'title' => 'required|string|max:255',
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
                'photo_id' => $validatedData['photo_id'],
                'user_id' => $validatedData['user_id'],
            ]
        );
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
