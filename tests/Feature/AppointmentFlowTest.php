<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_appointment(): void
    {
        $user = User::factory()->create();
        $service = Service::create([
            'name' => 'Consultation generale',
            'description' => 'Consultation standard',
            'price' => 150.00,
            'duration' => 30,
        ]);

        $this->actingAs($user)
            ->post('/appointments', [
                'service_id' => $service->id,
                'appointment_date' => now()->addDay()->format('Y-m-d H:i:s'),
                'notes' => 'Controle annuel',
            ])
            ->assertRedirect('/appointments');

        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'status' => 'pending',
            'notes' => 'Controle annuel',
        ]);
    }

    public function test_user_cannot_view_appointment_of_another_user(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $service = Service::create([
            'name' => 'Consultation specialisee',
            'description' => 'Cardiologie',
            'price' => 220.00,
            'duration' => 45,
        ]);

        $appointment = Appointment::create([
            'user_id' => $owner->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDays(2),
            'notes' => 'Dossier prive',
            'status' => 'pending',
        ]);

        $this->actingAs($otherUser)
            ->get("/appointments/{$appointment->id}")
            ->assertForbidden();
    }

    public function test_search_only_returns_authenticated_user_appointments(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $service = Service::create([
            'name' => 'Consultation ORL',
            'description' => 'Nez gorge oreille',
            'price' => 180.00,
            'duration' => 40,
        ]);

        Appointment::create([
            'user_id' => $user->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDays(1),
            'notes' => 'Mot-cle-secret',
            'status' => 'pending',
        ]);

        Appointment::create([
            'user_id' => $otherUser->id,
            'service_id' => $service->id,
            'appointment_date' => now()->addDays(1),
            'notes' => 'Mot-cle-secret',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($user)
            ->get('/appointments/search/results?q=Mot-cle-secret');

        $response->assertOk();
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['user_id' => $user->id]);
    }
}
