@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h1 class="page-title mb-1" style="font-family:'Playfair Display',serif;">
                @if(Auth::user()->role === 'patient')
                    {{ __('messages.my_appointments') }}
                @else
                    {{ __('messages.appointments_management') }}
                @endif
            </h1>
            <p class="text-muted mb-0 small">
                @if(Auth::user()->role === 'patient') {{ __('messages.subtitle') }}
                @else {{ __('messages.all_subtitle') }} @endif
            </p>
        </div>
        <div class="col-md-5 text-end d-flex justify-content-end gap-2 flex-wrap">
            @if(Auth::user()->role === 'patient')
                <button class="btn btn-outline-dark rounded-pill" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                    <i class="bi bi-lightning-charge me-1"></i>{{ __('messages.quick_add') }}
                </button>
                <a href="{{ route('appointments.create') }}" class="btn btn-dark rounded-pill">
                    <i class="bi bi-plus-circle me-1"></i>{{ __('messages.new_appointment') }}
                </a>
            @endif
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="position-relative">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0"
                           id="searchInput"
                           placeholder="{{ __('messages.search_placeholder') }}"
                           autocomplete="off">
                    <button class="btn btn-dark" type="button" id="clearSearch" style="display:none;">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <div id="searchResults" style="display:none;"></div>
            </div>
        </div>
        <div class="col-md-3">
            <select class="form-select" id="statusFilter">
                <option value="">{{ __('messages.all_statuses') }}</option>
                <option value="pending">{{ __('messages.pending') }}</option>
                <option value="confirmed">{{ __('messages.confirmed') }}</option>
                <option value="cancelled">{{ __('messages.cancelled') }}</option>
                <option value="completed">{{ __('messages.completed') }}</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="app-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="appointmentsTable">
                <thead>
                    <tr>
                        @if(Auth::user()->role !== 'patient')
                            <th>{{ __('messages.patient') }}</th>
                        @endif
                        <th>{{ __('messages.service') }}</th>
                        <th>{{ __('messages.date_time') }}</th>
                        <th>{{ __('messages.duration') }}</th>
                        <th>{{ __('messages.price') }}</th>
                        <th>{{ __('messages.status') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse($appointments as $appointment)
                    <tr data-status="{{ $appointment->status }}">
                        @if(Auth::user()->role !== 'patient')
                            <td><small class="fw-semibold">{{ $appointment->user->name ?? '-' }}</small></td>
                        @endif
                        <td>
                            <strong>{{ $appointment->service->name }}</strong>
                            @if($appointment->notes)
                                <br><small class="text-muted">{{ Str::limit($appointment->notes, 40) }}</small>
                            @endif
                        </td>
                        <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
                        <td><small>{{ $appointment->service->duration }} min</small></td>
                        <td><span class="badge bg-dark rounded-pill px-3">{{ number_format($appointment->service->price, 2) }} €</span></td>
                        <td>@include('partials.status-badge', ['status' => $appointment->status])</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('appointments.show', $appointment) }}" class="btn btn-sm btn-outline-dark rounded-circle" title="{{ __('messages.actions') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($appointment->status !== 'cancelled' && $appointment->status !== 'completed')
                                    @if(Auth::user()->role === 'patient')
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->role === 'patient' || Auth::user()->role === 'admin')
                                        <button class="btn btn-sm btn-dark rounded-circle"
                                                data-bs-toggle="modal" data-bs-target="#cancelModal"
                                                data-id="{{ $appointment->id }}"
                                                title="{{ __('messages.yes_cancel') }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                    @if(in_array(Auth::user()->role, ['medecin','admin']))
                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('appointments.confirm', $appointment) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-dark rounded-circle" title="{{ __('messages.confirm') }}">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($appointment->status === 'confirmed')
                                            <form action="{{ route('appointments.complete', $appointment) }}" method="POST" class="d-inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-outline-dark rounded-circle" title="{{ __('messages.complete') }}">
                                                    <i class="bi bi-check-all"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('appointments.edit', $appointment) }}" class="btn btn-sm btn-outline-secondary rounded-circle">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-calendar2-x" style="font-size:2.5rem; opacity:0.3; display:block; margin-bottom:1rem;"></i>
                            {{ __('messages.no_appointments') }}
                            @if(Auth::user()->role === 'patient')
                                <a href="{{ route('appointments.create') }}" class="d-block mt-2">{{ __('messages.create_one') }}</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
        <div class="p-3 border-top">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Cancel Modal --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" style="font-family:'Playfair Display',serif;">{{ __('messages.confirm_cancel') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-exclamation-triangle" style="font-size:3rem; color:#888;"></i>
                <p class="mt-3 mb-0">{{ __('messages.cancel_message') }}</p>
            </div>
            <div class="modal-footer border-0 justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.no_go_back') }}</button>
                <form id="cancelForm" method="POST" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-dark rounded-pill px-4">{{ __('messages.yes_cancel') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Quick Add Modal (patient only) --}}
@if(Auth::user()->role === 'patient')
<div class="modal fade" id="quickAddModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" style="font-family:'Playfair Display',serif;">{{ __('messages.quick_add') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('appointments.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('messages.service') }} <span class="text-danger">*</span></label>
                        <select class="form-select" name="service_id" required>
                            <option value="">{{ __('messages.select_service') }}</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}">{{ $service->name }} — {{ $service->duration }} min</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('messages.date_time') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control" name="appointment_date" required min="{{ now()->format('Y-m-d\TH:i') }}">
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="submit" class="btn btn-dark rounded-pill px-4">{{ __('messages.create') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Cancel modal
    document.getElementById('cancelModal').addEventListener('show.bs.modal', function (event) {
        const id = event.relatedTarget.getAttribute('data-id');
        document.getElementById('cancelForm').action = `/appointments/${id}`;
    });

    // ── Status filter
    document.getElementById('statusFilter').addEventListener('change', function () {
        const val = this.value;
        document.querySelectorAll('#tableBody tr[data-status]').forEach(row => {
            row.style.display = (!val || row.dataset.status === val) ? '' : 'none';
        });
    });

    // ── Axios live search
    const searchInput  = document.getElementById('searchInput');
    const searchResults= document.getElementById('searchResults');
    const clearBtn     = document.getElementById('clearSearch');
    let   searchTimer  = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(searchTimer);
        const q = this.value.trim();

        clearBtn.style.display = q ? 'block' : 'none';

        if (q.length < 2) {
            searchResults.style.display = 'none';
            return;
        }

        searchTimer = setTimeout(() => {
            axios.get('/appointments/search/results', { params: { q } })
                .then(res => {
                    const data = res.data;
                    if (!data.length) {
                        searchResults.innerHTML = `<div class="search-item text-muted">{{ __('messages.no_results') }}</div>`;
                    } else {
                        searchResults.innerHTML = data.map(a => `
                            <a href="/appointments/${a.id}" class="search-item d-flex justify-content-between align-items-center text-decoration-none text-dark">
                                <div>
                                    <strong>${a.service.name}</strong>
                                    ${a.user ? `<small class="text-muted ms-2">— ${a.user.name}</small>` : ''}
                                    <br><small class="text-muted">${new Date(a.appointment_date).toLocaleDateString('fr-FR')} ${new Date(a.appointment_date).toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'})}</small>
                                </div>
                                <span class="badge bg-secondary rounded-pill ms-3">${a.status}</span>
                            </a>`).join('');
                    }
                    searchResults.style.display = 'block';
                })
                .catch(() => { searchResults.style.display = 'none'; });
        }, 300);
    });

    clearBtn.addEventListener('click', () => {
        searchInput.value = '';
        searchResults.style.display = 'none';
        clearBtn.style.display = 'none';
    });

    document.addEventListener('click', e => {
        if (!e.target.closest('.position-relative')) {
            searchResults.style.display = 'none';
        }
    });
});
</script>
@endpush
