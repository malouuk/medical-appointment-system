<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'name' => 'Consultation Générale',
            'description' => 'Consultation médicale générale',
            'price' => 50.00,
            'duration' => 30,
        ]);

        Service::create([
            'name' => 'Consultation Spécialisée',
            'description' => 'Consultation avec un spécialiste',
            'price' => 100.00,
            'duration' => 45,
        ]);

        Service::create([
            'name' => 'Bilan de Santé',
            'description' => 'Examen complet de santé',
            'price' => 150.00,
            'duration' => 60,
        ]);

        Service::create([
            'name' => 'Suivi Post-Opératoire',
            'description' => 'Suivi après intervention chirurgicale',
            'price' => 75.00,
            'duration' => 30,
        ]);

        Service::create([
            'name' => 'Vaccination',
            'description' => 'Service de vaccination',
            'price' => 30.00,
            'duration' => 15,
        ]);
    }
}
