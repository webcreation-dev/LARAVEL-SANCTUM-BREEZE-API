<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Profil::create([
        //     'name' => 'Administrateur',
        // ]);
        // Profil::create([
        //     'name' => 'Employé',
        // ]);

        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'adjilan2403@gmail.com',
            'password' => bcrypt('password'),
            'profil_id' => '1',
        ]);
    }
}
