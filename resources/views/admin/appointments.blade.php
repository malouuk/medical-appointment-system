@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h1 class="page-title mb-1" style="font-family:'Playfair Display',serif;">Console d'Administration</h1>
            <p class="text-muted mb-0 small">Gestion globale des flux de rendez-vous et planification du cabinet.</p>
        </div>
        {{-- Advanced Search & Filters --}}
    <div class="app-card p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-7">
                <label class="form-label small fw-bold text-uppercase">Recherche Globale</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" id="adminSearch" placeholder="Patient, service, notes...">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-uppercase">Filtrer par Statut</label>
                <select class="form-select" id="adminStatusFilter">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="confirmed">Confirmés</option>
                    <option value="completed">Complétés</option>
                    <option value="cancelled">Annulés</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-dark w-100 rounded-pill"><i class="bi bi-funnel me-1"></i> Filtrer</button>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="app-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Patient</th>
                        <th>Service & Durée</th>
                        <th>Date & Heure</th>
                        <th>Statut</th>
                        <th class="text-end pe-4">Actions de Gestion</th>
                    </tr>
                </thead>
                <tbody id="adminTableBody">
                    @forelse($appointments as $appt)
                    <tr class="border-bottom">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width:40px; height:40px; font-weight:600;">
                                    {{ substr($appt->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $appt->user->name }}</div>
                                    <div class="text-muted small">{{ $appt->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $appt->service->name }}</div>
                            <div class="text-muted small"><i class="bi bi-clock me-1"></i>{{ $appt->service->duration }} min</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ $appt->appointment_date->format('d/m/Y') }}</div>
                            <div class="fw-bold">{{ $appt->appointment_date->format('H:i') }}</div>
                        </td>
                        <td>
                            @include('partials.status-badge', ['status' => $appt->status])
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                @if($appt->status === 'pending')
                                <form action="{{ route('appointments.confirm', $appt) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-dark rounded-pill px-3">Confirmer</button>
                                </form>
                                @endif
                                @if($appt->status === 'confirmed')
                                <form action="{{ route('appointments.complete', $appt) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm btn-outline-dark rounded-pill px-3">Clôturer</button>
                                </form>
                                @endif
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                                        <li><a class="dropdown-item" href="{{ route('appointments.show', $appt) }}"><i class="bi bi-eye me-2"></i>Détails</a></li>
                                        <li><a class="dropdown-item" href="{{ route('appointments.edit', $appt) }}"><i class="bi bi-pencil me-2"></i>Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('appointments.destroy', $appt) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button class="dropdown-item text-danger"><i class="bi bi-trash me-2"></i>Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            Aucun flux de rendez-vous détecté.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($appointments->hasPages())
        <div class="p-4 border-top d-flex justify-content-center">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('adminSearch');
    const statusFilter = document.getElementById('adminStatusFilter');
    const tableRows = document.querySelectorAll('#adminTableBody tr:not(.text-center)');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const statusTerm = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const statusBadge = row.querySelector('.badge-status');
            const status = statusBadge ? statusBadge.innerText.toLowerCase() : '';
            
            // Map common translations to technical status if needed, 
            // but here we check if the row content matches search AND status
            const matchesSearch = text.includes(searchTerm);
            
            // Since status badges contain translated text, we check the data or the text
            // In a real app we'd use data-attributes, let's keep it simple:
            let matchesStatus = true;
            if (statusTerm === 'pending') matchesStatus = text.includes('attente');
            else if (statusTerm === 'confirmed') matchesStatus = text.includes('confirmé');
            else if (statusTerm === 'completed') matchesStatus = text.includes('complété');
            else if (statusTerm === 'cancelled') matchesStatus = text.includes('annulé');

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>
@endpush
