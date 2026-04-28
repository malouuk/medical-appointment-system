@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-1" style="font-family: 'Playfair Display', serif;">Modifier le Rendez-vous</h1>
            <p class="text-muted mb-0">Consultation #{{ $appointment->id }} &mdash; {{ $appointment->service->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card app-card p-4">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Erreur :</h6>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('appointments.update', $appointment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Statut actuel --}}
                    <div class="mb-4 p-3 rounded-3 bg-light border">
                        <p class="text-uppercase text-muted mb-2" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Statut actuel</p>
                        @switch($appointment->status)
                            @case('pending')
                                <span class="badge bg-secondary rounded-pill px-3">En attente</span>
                                @break
                            @case('confirmed')
                                <span class="badge bg-dark rounded-pill px-3">Confirmé</span>
                                @break
                            @case('cancelled')
                                <span class="badge border border-dark text-dark rounded-pill px-3">Annulé</span>
                                @break
                            @case('completed')
                                <span class="badge bg-light text-dark border border-dark rounded-pill px-3">Complété</span>
                                @break
                        @endswitch
                    </div>

                    {{-- Service --}}
                    <div class="mb-4">
                        <label for="service_id" class="form-label fw-semibold">
                            Service <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('service_id') is-invalid @enderror"
                                id="service_id" name="service_id" required>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected($appointment->service_id == $service->id)>
                                    {{ $service->name }} — {{ $service->duration }} min — {{ number_format($service->price, 2) }} €
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Date & Heure --}}
                    <div class="mb-4">
                        <label for="appointment_date" class="form-label fw-semibold">
                            Date et Heure <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local"
                               class="form-control @error('appointment_date') is-invalid @enderror"
                               id="appointment_date"
                               name="appointment_date"
                               value="{{ $appointment->appointment_date->format('Y-m-d\TH:i') }}"
                               required
                               min="{{ now()->format('Y-m-d\TH:i') }}">
                        @error('appointment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label fw-semibold">Notes <span class="text-muted fw-normal">(optionnel)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror"
                                  id="notes"
                                  name="notes"
                                  rows="4"
                                  placeholder="Informations complémentaires...">{{ $appointment->notes }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="d-flex gap-2 justify-content-end">
                        <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-outline-dark rounded-pill px-4">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-dark rounded-pill px-4">
                            <i class="bi bi-floppy me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
