<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Appointment;

class AppointmentIndexTest extends TestCase
{
    public function test_index_renders()
    {
        $user = User::where('email', 'patient@mediluxe.com')->first();
        $response = $this->actingAs($user)->get('/appointments');
        
        file_put_contents('scratch_output.html', $response->getContent());
        echo "Status: " . $response->status() . "\n";
    }
}
