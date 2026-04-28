<?php

namespace App\Http\Controllers\Api;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;

class AppointmentApiController extends Controller
{
    /** Get appointments (role-filtered) */
    public function index()
    {
        $query = Appointment::with(['service', 'user']);

        if (Auth::user()->role === 'patient') {
            $query->where('user_id', Auth::id());
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $appointments,
            'message' => 'Appointments retrieved successfully'
        ]);
    }

    /** Create new appointment */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        $appointment = Appointment::create($validated);

        try {
            Mail::to(Auth::user()->email)->send(new AppointmentConfirmation($appointment));
        } catch (\Throwable $e) {
            \Log::warning('API Email failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'data'    => $appointment->load('service'),
            'message' => 'Appointment created successfully'
        ], 201);
    }

    /** Get single appointment */
    public function show(Appointment $appointment)
    {
        if (Auth::user()->role === 'patient' && $appointment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $appointment->load(['service', 'user'])
        ]);
    }

    /** Update appointment */
    public function update(Request $request, Appointment $appointment)
    {
        if (Auth::user()->role === 'patient' && $appointment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $appointment->update($validated);

        return response()->json([
            'success' => true,
            'data'    => $appointment->load('service'),
            'message' => 'Appointment updated successfully'
        ]);
    }

    /** Cancel appointment */
    public function destroy(Appointment $appointment)
    {
        if (Auth::user()->role === 'patient' && $appointment->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully'
        ]);
    }

    /** Get all services */
    public function services()
    {
        return response()->json([
            'success' => true,
            'data'    => Service::all()
        ]);
    }
}
