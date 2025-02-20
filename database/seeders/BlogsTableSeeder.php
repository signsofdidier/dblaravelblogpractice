<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
//        DB::table('blogs')->insert([
//            'title' => 'This is a title.',
//            'description' => 'This is a description for a blog post.',
//            'photo_id' => Photo::inRandomOrder()->first()->id,
//            'user_id' => User::inRandomOrder()->first()->id,
//            'created_at' => now(),
//            'updated_at' => now(),
//        ]);
//        Blog::factory()->count(12)->create();
    }
}
