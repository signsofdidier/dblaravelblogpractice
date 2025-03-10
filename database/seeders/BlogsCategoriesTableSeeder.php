<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogsCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Haal alle rollen op als een Eloquent-collectie
        $categories = Category::all();

        Blog::all()->each(function ($blog ) use ($categories) {
           // Bepaal een dynamisch aantal categories: min 1, max het totale aantal beschikbare categories
           $categoriesCount = rand(1, $categories->count());

            // Haal een willekeurig aantal unieke rollen op en zorg dat het altijd een array is
            $selectedCategories = $categories->random($categoriesCount)->pluck('id')->toArray();

            // Koppel deze rollen aan de gebruiker
            $blog->categories()->attach($selectedCategories);
        });

    }
}
