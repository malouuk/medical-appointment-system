@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="page-title mb-1" style="font-family: 'Playfair Display', serif;">Détails du Rendez-vous</h1>
            <p class="text-muted mb-0">Consultation #{{ $appointment->id }}</p>
        </div>
        <div class="col-md-4 text-end d-flex justify-content-end gap-2">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline-dark rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Retour
            </a>
            @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-dark rounded-pill px-4">
                    <i class="bi bi-pencil me-1"></i> Modifier
                </a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- Infos principales --}}
        <div class="col-md-8">
            <div class="card app-card p-4">
                {{-- Service --}}
                <div class="d-flex align-items-start gap-4 mb-4 pb-4 border-bottom">
                    <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center text-white flex-shrink-0"
                         style="width:56px; height:56px; font-size:1.5rem;">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1" style="font-family:'Playfair Display',serif;">{{ $appointment->service->name }}</h5>
                        <p class="text-muted mb-0 small">{{ $appointment->service->description }}</p>
                    </div>
                </div>

                {{-- Details grid --}}
                <div class="row g-4">
                    <div class="col-sm-6">
                        <p class="text-uppercase text-muted mb-1" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Date & Heure</p>
                        <p class="fw-bold mb-0 fs-5">{{ $appointment->appointment_date->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-uppercase text-muted mb-1" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Durée</p>
                        <p class="fw-bold mb-0 fs-5">{{ $appointment->service->duration }} minutes</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-uppercase text-muted mb-1" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Prix</p>
                        <p class="fw-bold mb-0 fs-5">{{ number_format($appointment->service->price, 2) }} €</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-uppercase text-muted mb-1" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Patient</p>
                        <p class="fw-bold mb-0 fs-5">{{ $appointment->user->name }}</p>
                    </div>
                </div>

                @if($appointment->notes)
                    <div class="mt-4 pt-4 border-top">
                        <p class="text-uppercase text-muted mb-2" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Notes</p>
                        <p class="mb-0" style="line-height:1.8;">{{ $appointment->notes }}</p>
                    </div>
                @endif

                <div class="mt-4 pt-4 border-top text-muted small">
                    <span><i class="bi bi-clock me-1"></i> Créé le {{ $appointment->created_at->format('d/m/Y à H:i') }}</span>
                    &nbsp;·&nbsp;
                    <span>Modifié le {{ $appointment->updated_at->format('d/m/Y à H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Statut & Actions --}}
        <div class="col-md-4">
            <div class="card app-card p-4 mb-4">
                <p class="text-uppercase text-muted mb-3" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Statut</p>
                @switch($appointment->status)
                    @case('pending')
                        <span class="badge bg-secondary rounded-pill px-4 py-2 fs-6"><i class="bi bi-hourglass-split me-2"></i>En attente</span>
                        @break
                    @case('confirmed')
                        <span class="badge bg-dark rounded-pill px-4 py-2 fs-6"><i class="bi bi-check-circle me-2"></i>Confirmé</span>
                        @break
                    @case('cancelled')
                        <span class="badge border border-dark text-dark rounded-pill px-4 py-2 fs-6"><i class="bi bi-x-circle me-2"></i>Annulé</span>
                        @break
                    @case('completed')
                        <span class="badge bg-light text-dark border border-dark rounded-pill px-4 py-2 fs-6"><i class="bi bi-check-all me-2"></i>Complété</span>
                        @break
                @endswitch
            </div>

            @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
            <div class="card app-card p-4">
                <p class="text-uppercase text-muted mb-3" style="font-size:0.7rem; letter-spacing:0.1em; font-weight:600;">Actions</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-dark rounded-pill">
                        <i class="bi bi-pencil me-2"></i>Modifier ce rendez-vous
                    </a>
                    <button class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="bi bi-x-circle me-2"></i>Annuler ce rendez-vous
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal d'annulation --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" style="font-family:'Playfair Display',serif;">Confirmer l'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color:#888;"></i>
                <p class="mt-3 mb-0">Êtes-vous sûr de vouloir annuler ce rendez-vous ?<br><small class="text-muted">Cette action ne peut pas être annulée.</small></p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2 pt-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Non, revenir</button>
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-dark rounded-pill px-4">Oui, annuler</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
