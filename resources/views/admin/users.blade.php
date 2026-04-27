@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h1 class="page-title mb-1" style="font-family:'Playfair Display',serif;">{{ __('messages.users_management') }}</h1>
            <p class="text-muted mb-0 small">{{ __('messages.users_list') }}</p>
        </div>
    </div>

    {{-- Admin Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $stats['total_users'] }}</div>
                <div class="text-muted small mt-1">Utilisateurs</div>
                <i class="bi bi-people text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $stats['total_patients'] }}</div>
                <div class="text-muted small mt-1">{{ __('messages.total_patients') }}</div>
                <i class="bi bi-person text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $stats['total_doctors'] }}</div>
                <div class="text-muted small mt-1">{{ __('messages.total_doctors') }}</div>
                <i class="bi bi-heart-pulse text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card text-center">
                <div class="stat-number">{{ $stats['total_appts'] }}</div>
                <div class="text-muted small mt-1">{{ __('messages.total_appointments') }}</div>
                <i class="bi bi-calendar-check text-secondary mt-2 d-block fs-4"></i>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="app-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>{{ __('messages.user_name') }}</th>
                        <th>{{ __('messages.user_email') }}</th>
                        <th>{{ __('messages.user_role') }}</th>
                        <th>{{ __('messages.user_created') }}</th>
                        <th>{{ __('messages.user_appointments') }}</th>
                        <th>{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center" style="width:32px; height:32px; font-size:0.8rem;">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>
                        <td><small>{{ $user->email }}</small></td>
                        <td>
                            @switch($user->role)
                                @case('admin')
                                    <span class="badge bg-dark rounded-pill px-3">{{ __('messages.admin') }}</span>
                                    @break
                                @case('medecin')
                                    <span class="badge bg-secondary rounded-pill px-3">{{ __('messages.doctor') }}</span>
                                    @break
                                @default
                                    <span class="badge border border-dark text-dark rounded-pill px-3">{{ __('messages.patient') }}</span>
                            @endswitch
                        </td>
                        <td><small>{{ $user->created_at->format('d/m/Y') }}</small></td>
                        <td><span class="badge bg-light text-dark border">{{ $user->appointments_count }}</span></td>
                        <td>
                            @if($user->id !== Auth::id())
                            <form action="{{ route('admin.users.role', $user) }}" method="POST" class="d-flex gap-2">
                                @csrf @method('PATCH')
                                <select name="role" class="form-select form-select-sm rounded-pill" style="width:130px;" onchange="this.form.submit()">
                                    <option value="patient" @selected($user->role === 'patient')>{{ __('messages.patient') }}</option>
                                    <option value="medecin" @selected($user->role === 'medecin')>{{ __('messages.doctor') }}</option>
                                    <option value="admin" @selected($user->role === 'admin')>{{ __('messages.admin') }}</option>
                                </select>
                            </form>
                            @else
                            <small class="text-muted"><em>(Vous-même)</em></small>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
