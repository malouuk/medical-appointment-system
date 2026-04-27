@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h1 class="page-title mb-1" style="font-family:'Playfair Display',serif;">{{ __('messages.new_appointment') }}</h1>
            <p class="text-muted mb-0 small">{{ __('messages.subtitle') }}</p>
        </div>
        <div class="col-md-5 text-end">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> {{ __('messages.back') }}
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i>Erreur :</h6>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Form Side --}}
        <div class="col-md-7">
            <div class="app-card p-4">
                <form action="{{ route('appointments.store') }}" method="POST" id="appointmentForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="service_id" class="form-label fw-semibold">{{ __('messages.service') }} <span class="text-danger">*</span></label>
                        <select class="form-select rounded-3 @error('service_id') is-invalid @enderror" 
                                id="service_id" name="service_id" required>
                            <option value="">{{ __('messages.select_service') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} — {{ $service->duration }} min — {{ number_format($service->price, 2) }} €
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="appointment_date" class="form-label fw-semibold">{{ __('messages.date_time') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" 
                               class="form-control rounded-3 @error('appointment_date') is-invalid @enderror" 
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
                        <label for="notes" class="form-label fw-semibold">{{ __('messages.notes_optional') }}</label>
                        <textarea class="form-control rounded-3 @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" 
                                  placeholder="Indiquez vos symptômes ou besoins particuliers...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-dark rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-calendar-plus me-2"></i>{{ __('messages.create_appointment') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info Side (Service Selection Cards) --}}
        <div class="col-md-5">
            <h5 class="fw-bold mb-4" style="font-family:'Playfair Display',serif;">Nos Services</h5>
            <div class="row g-3">
                @foreach($services as $service)
                    <div class="col-12">
                        <div class="app-card p-3 service-card h-100 border-start border-5 border-dark" 
                             style="cursor: pointer; transition: 0.3s;" 
                             data-id="{{ $service->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $service->name }}</h6>
                                    <p class="text-muted small mb-0">{{ Str::limit($service->description, 60) }}</p>
                                </div>
                                <span class="badge bg-dark rounded-pill">{{ number_format($service->price, 2) }} €</span>
                            </div>
                            <div class="mt-2 text-end">
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $service->duration }} min</small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 p-4 rounded-4 bg-white border shadow-sm">
                <h6 class="fw-bold mb-2"><i class="bi bi-shield-check me-2"></i>Confidentialité</h6>
                <p class="text-muted small mb-0">Tous vos rendez-vous sont soumis au secret médical et stockés de manière sécurisée.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const serviceSelect = document.getElementById('service_id');
    
    // Auto-select service when card is clicked
    document.querySelectorAll('.service-card').forEach(card => {
        card.addEventListener('click', function() {
            const serviceId = this.getAttribute('data-id');
            serviceSelect.value = serviceId;
            
            // Visual feedback
            document.querySelectorAll('.service-card').forEach(c => c.classList.remove('border-primary'));
            this.classList.add('border-primary');
            
            // Scroll back to select
            serviceSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Flash effect
            serviceSelect.classList.add('is-valid');
            setTimeout(() => serviceSelect.classList.remove('is-valid'), 1000);
        });
    });
});
</script>
@endpush
