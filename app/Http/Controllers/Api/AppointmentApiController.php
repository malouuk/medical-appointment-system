<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentApiController extends Controller
{
    /**
     * Get all appointments for authenticated user
     */
    public function index()
    {
        $appointments = Appointment::with(['service'])
            ->where('user_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments,
            'message' => 'Rendez-vous récupérés avec succès'
        ]);
    }

    /**
     * Create a new appointment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        $appointment = Appointment::create($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment->load('service'),
            'message' => 'Rendez-vous créé avec succès'
        ], 201);
    }

    /**
     * Get single appointment
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $appointment->load('service')
        ]);
    }

    /**
     * Update appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data' => $appointment->load('service'),
            'message' => 'Rendez-vous modifié avec succès'
        ]);
    }

    /**
     * Delete appointment
     */
    public function destroy(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Rendez-vous annulé avec succès'
        ]);
    }

    /**
     * Get all services
     */
    public function services()
    {
        $services = Service::all();

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }
}
