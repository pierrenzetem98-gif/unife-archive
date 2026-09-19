<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Creazione dell'account Amministratore Pierre Nzetem
        User::firstOrCreate(
            ['email' => 'pierre.nzetem@edu.unife.it'],
            [
                'name' => 'Pierre Nzetem',
                'password' => Hash::make('Nzetemcr7@'),
                'role' => 'admin',
            ]
        );

        // Creazione di uno studente di prova
        User::firstOrCreate(
            ['email' => 'studente@edu.unife.it'],
            [
                'name' => 'Studente UniFe',
                'password' => Hash::make('password123'),
                'role' => 'student',
            ]
        );

        // Inserimento materie ed esami
        $this->call([
            SubjectSeeder::class,
        ]);
    }
}