<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;

class AppointmentController extends Controller
{
    // Afficher tous les rendez-vous du patient ou du médecin
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.users')->with('error', 'L\'administrateur ne gère pas directement les rendez-vous.');
        }

        $query = Appointment::with(['service', 'user']);

        if (Auth::user()->role === 'patient') {
            $query->where('user_id', Auth::id());
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10);
        $services     = Service::all();

        return view('appointments.index', compact('appointments', 'services'));
    }

    // Afficher le formulaire pour créer un rendez-vous
    public function create()
    {
        $services = Service::all();
        return view('appointments.create', compact('services'));
    }

    // Enregistrer un nouveau rendez-vous dans la base de données
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes'            => 'nullable|string|max:1000',
        ], [
            'service_id.required'       => 'Le service est requis.',
            'appointment_date.required' => 'La date est requise.',
            'appointment_date.after'    => 'La date doit être dans le futur.',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status']  = 'pending';

        $appointment = Appointment::create($validated);

        // Envoyer un email de confirmation
        try {
            Mail::to(Auth::user()->email)->send(new AppointmentConfirmation($appointment));
        } catch (\Throwable $e) {
            // Si l'email ne s'envoie pas, on enregistre l'erreur mais on continue
        }

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous créé avec succès !');
    }

    // Afficher les détails d'un rendez-vous
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('appointments.show', compact('appointment'));
    }

    // Afficher le formulaire d'édition d'un rendez-vous
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $services = Service::all();
        return view('appointments.edit', compact('appointment', 'services'));
    }

    // Modifier un rendez-vous existant
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $validated = $request->validate([
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:now',
            'notes'            => 'nullable|string|max:1000',
        ], [
            'appointment_date.after' => 'La date doit être dans le futur.',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.show', $appointment)
            ->with('success', 'Rendez-vous modifié avec succès !');
    }

    // Annuler un rendez-vous (marquer comme 'cancelled')
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('appointments.index')
            ->with('success', 'Rendez-vous annulé avec succès.');
    }

    // Le médecin ou admin peut confirmer un rendez-vous
    public function confirm(Appointment $appointment)
    {
        abort_if(!in_array(Auth::user()->role, ['admin', 'medecin']), 403);
        $appointment->update(['status' => 'confirmed']);
        return back()->with('success', 'Rendez-vous confirmé.');
    }

    // Le médecin ou admin marque un rendez-vous comme fait
    public function complete(Appointment $appointment)
    {
        abort_if(!in_array(Auth::user()->role, ['admin', 'medecin']), 403);
        $appointment->update(['status' => 'completed']);
        return back()->with('success', 'Rendez-vous marqué comme complété.');
    }

    // Chercher les rendez-vous en temps réel (pour Axios)
    public function search(Request $request)
    {
        $query = trim((string) $request->get('q', ''));

        if ($query === '') {
            return response()->json([]);
        }

        $builder = Appointment::with(['service', 'user']);

        if (Auth::user()->role === 'patient') {
            $builder->where('user_id', Auth::id());
        }

        $results = $builder->where(function ($q) use ($query) {
                $q->whereHas('service', fn($s) => $s->where('name', 'like', "%{$query}%"))
                  ->orWhere('notes', 'like', "%{$query}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$query}%"));
            })
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
}
