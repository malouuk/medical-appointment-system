@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>Mes Rendez-vous</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Nouveau Rendez-vous
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Chercher un rendez-vous...">
                <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <select class="form-select" id="statusFilter">
                <option value="">Tous les statuts</option>
                <option value="pending">En attente</option>
                <option value="confirmed">Confirmé</option>
                <option value="cancelled">Annulé</option>
                <option value="completed">Complété</option>
            </select>
        </div>
    </div>

    <!-- Tableau des rendez-vous -->
    <div class="table-responsive">
        <table class="table table-hover" id="appointmentsTable">
            <thead class="table-light">
                <tr>
                    <th>Service</th>
                    <th>Date & Heure</th>
                    <th>Durée</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                    <tr class="appointment-row" data-id="{{ $appointment->id }}">
                        <td>
                            <strong>{{ $appointment->service->name }}</strong>
                            @if($appointment->notes)
                                <br><small class="text-muted">{{ Str::limit($appointment->notes, 50) }}</small>
                            @endif
                        </td>
                        <td>
                            {{ $appointment->appointment_date->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            {{ $appointment->service->duration }} min
                        </td>
                        <td>
                            <span class="badge bg-success">{{ number_format($appointment->service->price, 2) }} €</span>
                        </td>
                        <td>
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
                        </td>
                        <td>
                            <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                                <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif
                            @if($appointment->status !== 'cancelled')
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#cancelModal" data-id="{{ $appointment->id }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun rendez-vous trouvé. <a href="{{ route('appointments.create') }}">Créer un rendez-vous</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        {{ $appointments->links() }}
    </nav>
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
                Êtes-vous sûr de vouloir annuler ce rendez-vous ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non, revenir</button>
                <form id="cancelForm" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Oui, annuler le rendez-vous</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du modal d'annulation
    document.getElementById('cancelModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const appointmentId = button.getAttribute('data-id');
        const form = document.getElementById('cancelForm');
        form.action = `/appointments/${appointmentId}`;
    });

    // Recherche dynamique
    document.getElementById('searchBtn').addEventListener('click', searchAppointments);
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if(e.key === 'Enter') searchAppointments();
    });

    function searchAppointments() {
        const query = document.getElementById('searchInput').value;
        if(query.length < 1) return;

        fetch(`/appointments/search/results?q=${query}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.querySelector('#appointmentsTable tbody');
                tbody.innerHTML = '';
                
                if(data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Aucun résultat</td></tr>';
                    return;
                }

                data.forEach(appointment => {
                    const statusBadge = getStatusBadge(appointment.status);
                    const row = `
                        <tr>
                            <td>${appointment.service.name}</td>
                            <td>${new Date(appointment.appointment_date).toLocaleDateString('fr-FR')} ${new Date(appointment.appointment_date).toLocaleTimeString('fr-FR', {hour:'2-digit', minute:'2-digit'})}</td>
                            <td>${appointment.service.duration} min</td>
                            <td><span class="badge bg-success">${parseFloat(appointment.service.price).toFixed(2)} €</span></td>
                            <td>${statusBadge}</td>
                            <td>
                                <a href="/appointments/${appointment.id}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    `;
                    tbody.innerHTML += row;
                });
            });
    }

    function getStatusBadge(status) {
        const badges = {
            'pending': '<span class="badge bg-warning">En attente</span>',
            'confirmed': '<span class="badge bg-success">Confirmé</span>',
            'cancelled': '<span class="badge bg-danger">Annulé</span>',
            'completed': '<span class="badge bg-info">Complété</span>'
        };
        return badges[status] || '';
    }
});
</script>
@endsection
