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

        Profil::create([
            'name' => 'Administrateur',
        ]);
        Profil::create([
            'name' => 'Employé',
        ]);

        Profil::create([
            'name' => 'Administrateur',
            'name' => 'Employé',
            'name' => 'Manager',
        ]);

        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'adjilan2403@gmail.com',
            'password' => bcrypt('password'),
            'profil_id' => '1',
        ]);

        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'charbelzeusmamlankou@gmail.com',
            'password' => bcrypt('1234567890'),
            'profil_id' => '1',
        ]);

        User::factory()->create([
            'name' => 'Administrateur',
            'email' => 'visionnetteoptiquepkou@gmail.com',
            'password' => bcrypt('visionette@2023'),
            'profil_id' => '1',
        ]);

        // User::factory()->create([
        //     'name' => 'Employé',
        //     'email' => 'charbelmamlankou@gmail.com',
        //     'password' => bcrypt('1234567890'),
        //     'profil_id' => '2',
        // ]);
    }
}
