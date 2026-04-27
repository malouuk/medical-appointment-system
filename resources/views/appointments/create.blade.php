@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Créer un nouveau rendez-vous</h4>
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

                    <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
                        @csrf

                        <div class="mb-4">
                            <label for="service_id" class="form-label">
                                <strong>Service</strong>
                                <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('service_id') is-invalid @enderror" 
                                    id="service_id" name="service_id" required>
                                <option value="">-- Sélectionner un service --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>
                                        {{ $service->name }} - {{ $service->duration }} min - {{ number_format($service->price, 2) }} €
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <span id="serviceDescription"></span>
                            </small>
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
                                   value="{{ old('appointment_date') }}"
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
                                      rows="4"
                                      placeholder="Ajouter des notes supplémentaires...">{{ old('notes') }}</textarea>
                            <small class="text-muted d-block mt-2">
                                Caractères restants: <span id="charCount">1000</span>/1000
                            </small>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Créer le Rendez-vous
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Calendrier des services -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Services Disponibles</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($services as $service)
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-3 service-card" style="cursor: pointer;">
                                    <h6 class="mb-1">{{ $service->name }}</h6>
                                    <p class="text-muted small mb-2">{{ $service->description }}</p>
                                    <div class="d-flex justify-content-between">
                                        <span class="badge bg-info">{{ $service->duration }} min</span>
                                        <span class="badge bg-success">{{ number_format($service->price, 2) }} €</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceSelect = document.getElementById('service_id');
    const servicesData = {!! json_encode($services) !!};

    // Mettre à jour la description du service
    serviceSelect.addEventListener('change', function() {
        const service = serviceSelect.value 
            ? serviceSelect.options[serviceSelect.selectedIndex].text 
            : '';
        const descElement = document.getElementById('serviceDescription');
        
        if(serviceSelect.value) {
            const selected = serviceSelect.options[serviceSelect.selectedIndex];
            descElement.textContent = 'Nombre de caractères: ' + selected.text.length;
        } else {
            descElement.textContent = '';
        }
    });

    // Compteur de caractères
    document.getElementById('notes').addEventListener('input', function() {
        const remaining = 1000 - this.value.length;
        document.getElementById('charCount').textContent = remaining;
        
        if(remaining < 0) {
            this.value = this.value.substring(0, 1000);
        }
    });

    // Sélectionner un service
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function() {
            const serviceIndex = Array.from(document.querySelectorAll('.service-card')).indexOf(this) + 1;
            serviceSelect.value = serviceIndex;
            serviceSelect.dispatchEvent(new Event('change'));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
});
</script>
@endsection
