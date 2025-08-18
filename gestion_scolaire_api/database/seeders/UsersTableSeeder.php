<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          User::create([
            'name' => 'admin',
            'prenom' => 'admin',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('admin123'),
            'role' => 'Administrateur'
        ]);

        User::create([
            'name' => 'visiteur',
            'prenom' => 'visiteur',
            'username' => 'visiteur',
            'email' => 'visiteur@mail.com',
            'password' => Hash::make('visiteur123'),
            'role' => 'visiteur'
        ]);

        User::create([
            'name' => 'Diaby',
            'prenom' => 'Oumar',
            'username' => 'directeur',
            'email' => 'directeur@ecolegestion.com',
            'password' => Hash::make('directeur123'),
            'role' => 'Directeur'
        ]);

        User::create([
            'name' => 'Baldé',
            'prenom' => 'Kadiatou',
            'username' => 'secretaire',
            'email' => 'secretaire@ecolegestion.com',
            'password' => Hash::make('secretaire123'),
            'role' => 'Secrétaire'
        ]);

        User::create([
            'name' => 'Diallo',
            'prenom' => 'Binta',
            'username' => 'comptable',
            'email' => 'comptable@ecolegestion.com',
            'password' => Hash::make('comptable123'),
            'role' => 'Comptable'
        ]);

        User::create([
            'name' => 'Camara',
            'prenom' => 'Fodé',
            'username' => 'enseignant',
            'email' => 'enseignant@ecolegestion.com',
            'password' => Hash::make('enseignant123'),
            'role' => 'Enseignant'
        ]);

        User::create([
            'name' => 'Sow',
            'name' => 'Mariame',
            'username' => 'visiteur',
            'email' => 'visiteur@ecolegestion.com',
            'password' => Hash::make('visiteur123'),
            'role' => 'Visiteur'
        ]);
    }



}

