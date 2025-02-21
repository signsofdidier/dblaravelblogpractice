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
        DB::table('blogs')->insert([
            [
                'title' => '🌿 Waarom minimalisme je leven kan veranderen',
                'description' => 'In een wereld vol prikkels en spullen kiezen steeds meer mensen voor minimalisme. Het gaat niet alleen om minder bezitten, maar vooral om meer leven. Door bewust te kiezen wat écht waarde heeft, creëer je ruimte — letterlijk én mentaal. Minder spullen betekent minder opruimen, minder stress en meer tijd voor de dingen die er écht toe doen. Begin klein: ruim een lade op, doneer kleding die je niet meer draagt. Je zult merken hoeveel rust dat geeft.',
                'photo_id' => Photo::inRandomOrder()->first()->id,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '☕ De magie van je ochtendroutine',
                'description' => 'Hoe je je ochtend begint, bepaalt vaak de toon voor de rest van je dag. Een sterke ochtendroutine kan je productiever, gelukkiger en rustiger maken. Sta iets eerder op, drink een glas water, beweeg tien minuten en schrijf kort je doelen op voor de dag. Voeg iets toe wat je gelukkig maakt, zoals lezen of meditatie. Het hoeft niet ingewikkeld te zijn — het gaat erom dat je de dag start met intentie.',
                'photo_id' => Photo::inRandomOrder()->first()->id,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => '💡 Drie simpele hacks om productiever te werken',
                'description' => '1. De Pomodoro-techniek — Werk 25 minuten gefocust en neem daarna 5 minuten pauze. Na vier rondes een langere pauze. Zo houd je focus zonder uitgeput te raken.
2. Begin met de moeilijkste taak — Ook wel “the frog” genoemd. Als je ’s ochtends de lastigste klus klaart, voelt de rest van de dag lichter.
3. Minimaliseer afleidingen — Zet meldingen uit en gebruik apps als "Focus To-Do" om je geconcentreerd te houden.',
                'photo_id' => Photo::inRandomOrder()->first()->id,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
//        Blog::factory()->count(3)->create();
    }
}
