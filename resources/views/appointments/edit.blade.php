@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">Modifier le rendez-vous</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Erreur !</strong>
                            <ul class="mb-0">
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

                        <div class="mb-4">
                            <label for="service_id" class="form-label">
                                <strong>Service</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('service_id') is-invalid @enderror" 
                                    id="service_id" name="service_id" required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" @selected($appointment->service_id == $service->id)>
                                        {{ $service->name }} - {{ $service->duration }} min - {{ number_format($service->price, 2) }} €
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="appointment_date" class="form-label">
                                <strong>Date et Heure</strong>
                                <span class="text-danger">*</span>
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

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes" 
                                      name="notes"
                                      rows="4">{{ $appointment->notes }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Statut actuel :</strong>
                            @switch($appointment->status)
                                @case('pending')
                                    <span class="badge bg-warning">En attente</span>
                                    @break
                                @case('confirmed')
                                    <span class="badge bg-success">Confirmé</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-danger">Annulé</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-info">Complété</span>
                                    @break
                            @endswitch
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
