<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Remplir la base de données avec les données de test
     */
    public function run(): void
    {
        // Créer les utilisateurs principaux (Admin, Médecin, Patient)
        $admin = User::create([
            'name' => 'Administrateur',
            'email' => 'admin@mediluxe.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $medecin = User::create([
            'name' => 'Dr. Dupont',
            'email' => 'medecin@mediluxe.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'medecin',
        ]);

        $patient = User::create([
            'name' => 'Jean Patient',
            'email' => 'patient@mediluxe.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'patient',
        ]);

        // Créer 10 utilisateurs aléatoires supplémentaires (patients)
        User::factory(10)->create();

        // Appeler les seeders pour les services
        $this->call([
            ServiceSeeder::class,
        ]);

        // Créer 20 rendez-vous aléatoires
        \App\Models\Appointment::factory(20)->create();
    }
}
