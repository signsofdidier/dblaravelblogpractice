<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Haal alle rollen op als een Eloquent-collectie
        $roles = Role::all();

        User::all()->each(function ($user) use ($roles) {

        // Bepaal een dynamisch aantal rollen: min 1, max het totale aantal beschikbare rollen
        $roleCount = rand(1, $roles->count());

        // Haal een willekeurig aantal unieke rollen op en zorg dat het altijd een array i
        $selectedRoles = $roles->random($roleCount)->pluck('id')->toArray();

        // Koppel deze rollen aan de gebruiker
        $user->roles()->attach($selectedRoles);
    });

    }
}
