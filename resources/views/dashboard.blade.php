@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div>
            <h1 class="page-title mb-0" style="font-family:'Playfair Display',serif;">
                {{ __('messages.welcome') }}, {{ Auth::user()->name }} 👋
            </h1>
            <p class="text-muted mb-0 small">
                @if(Auth::user()->role === 'admin') {{ __('messages.admin_dashboard') }}
                @elseif(Auth::user()->role === 'medecin') {{ __('messages.doctor_dashboard') }}
                @else {{ __('messages.patient_dashboard') }}
                @endif
            </p>
        </div>
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $total }}</div>
                <div class="text-muted small mt-1">{{ __('messages.total_appointments') }}</div>
                <i class="bi bi-calendar2-check text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $pending }}</div>
                <div class="text-muted small mt-1">{{ __('messages.pending_count') }}</div>
                <i class="bi bi-hourglass-split text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $confirmed }}</div>
                <div class="text-muted small mt-1">{{ __('messages.confirmed_count') }}</div>
                <i class="bi bi-check-circle text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        @if(in_array(Auth::user()->role, ['admin','medecin']))
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $patients }}</div>
                <div class="text-muted small mt-1">{{ __('messages.total_patients') }}</div>
                <i class="bi bi-people text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- Recent appointments --}}
        <div class="{{ in_array(Auth::user()->role, ['admin','medecin']) ? 'col-md-8' : 'col-md-8' }}">
            <div class="app-card p-4 h-100">
                <h6 class="fw-bold mb-4" style="font-size:0.75rem; letter-spacing:0.1em; text-transform:uppercase;">
                    {{ __('messages.recent_activity') }}
                </h6>

                @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.service') }}</th>
                                @if(Auth::user()->role !== 'patient') <th>{{ __('messages.patient') }}</th> @endif
                                <th>{{ __('messages.date_time') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appt)
                            <tr>
                                <td><strong>{{ $appt->service->name ?? '-' }}</strong></td>
                                @if(Auth::user()->role !== 'patient')
                                    <td><small>{{ $appt->user->name ?? '-' }}</small></td>
                                @endif
                                <td><small>{{ $appt->appointment_date->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    @include('partials.status-badge', ['status' => $appt->status])
                                </td>
                                <td>
                                    <a href="{{ route('appointments.show', $appt) }}" class="btn btn-sm btn-outline-dark rounded-circle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar2-x" style="font-size:3rem; opacity:0.2;"></i>
                    <p class="mt-3 mb-0">{{ __('messages.no_recent') }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="col-md-4">
            <div class="app-card p-4 h-100">
                <h6 class="fw-bold mb-4" style="font-size:0.75rem; letter-spacing:0.1em; text-transform:uppercase;">
                    {{ __('messages.quick_actions') }}
                </h6>
                <div class="d-grid gap-3">
                    @if(Auth::user()->role === 'patient')
                        <a href="{{ route('appointments.create') }}" class="btn btn-dark rounded-pill py-2">
                            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.new_appointment') }}
                        </a>
                        <a href="{{ route('appointments.index') }}" class="btn btn-outline-dark rounded-pill py-2">
                            <i class="bi bi-calendar2-week me-2"></i>{{ __('messages.my_appointments') }}
                        </a>
                    @else
                        <a href="{{ route('appointments.index') }}" class="btn btn-dark rounded-pill py-2">
                            <i class="bi bi-calendar2-week me-2"></i>{{ __('messages.all_appointments') }}
                        </a>
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-dark rounded-pill py-2">
                            <i class="bi bi-people me-2"></i>{{ __('messages.users_management') }}
                        </a>
                        @endif
                    @endif
                    <a href="{{ route('profile') }}" class="btn btn-outline-secondary rounded-pill py-2">
                        <i class="bi bi-person-circle me-2"></i>{{ __('messages.my_profile') }}
                    </a>
                </div>
                <hr class="my-4">
                <div class="text-center text-muted small">
                    <i class="bi bi-shield-check fs-2 d-block mb-2" style="opacity:0.3;"></i>
                    {{ __('messages.security_note') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
