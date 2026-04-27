<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour créer des rendez-vous de test
 */
class AppointmentFactory extends Factory
{
    // Définir les données par défaut d'un rendez-vous
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'service_id' => Service::inRandomOrder()->first()->id ?? Service::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+1 month'),
            'notes' => fake()->optional()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
        ];
    }
}
