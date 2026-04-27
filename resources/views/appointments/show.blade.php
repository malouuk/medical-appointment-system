@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Détails du Rendez-vous</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Service</h6>
                            <p class="h5">{{ $appointment->service->name }}</p>
                            <p class="text-muted">{{ $appointment->service->description }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <h6 class="text-muted text-uppercase">Statut</h6>
                            @switch($appointment->status)
                                @case('pending')
                                    <p><span class="badge bg-warning" style="font-size: 14px;">En attente</span></p>
                                    @break
                                @case('confirmed')
                                    <p><span class="badge bg-success" style="font-size: 14px;">Confirmé</span></p>
                                    @break
                                @case('cancelled')
                                    <p><span class="badge bg-danger" style="font-size: 14px;">Annulé</span></p>
                                    @break
                                @case('completed')
                                    <p><span class="badge bg-info" style="font-size: 14px;">Complété</span></p>
                                    @break
                            @endswitch
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Date & Heure</h6>
                            <p class="h5">{{ $appointment->appointment_date->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Durée</h6>
                            <p class="h5">{{ $appointment->service->duration }} minutes</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Prix</h6>
                            <p class="h5"><span class="badge bg-success">{{ number_format($appointment->service->price, 2) }} €</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted text-uppercase">Patient</h6>
                            <p class="h5">{{ $appointment->user->name }}</p>
                        </div>
                    </div>

                    @if($appointment->notes)
                        <hr>
                        <div class="mb-4">
                            <h6 class="text-muted text-uppercase">Notes</h6>
                            <p class="lead">{{ $appointment->notes }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="row">
                        <div class="col-md-6 text-muted small">
                            <p>Créé le: {{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                            <p>Modifié le: {{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="{{ route('appointments.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour à la liste
                        </a>
                        @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                            <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Modifier
                            </a>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="bi bi-trash"></i> Annuler
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation d'annulation -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer l'annulation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Êtes-vous sûr de vouloir annuler ce rendez-vous ? Cette action ne peut pas être annulée.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, revenir</button>
                <form action="{{ route('appointments.destroy', $appointment) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Oui, annuler le rendez-vous</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
