@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4 align-items-center">
        <div class="col-md-12">
            <h1 class="page-title mb-1" style="font-family:'Playfair Display',serif;">{{ __('messages.profile_title') }}</h1>
            <p class="text-muted mb-0 small">Gérez vos informations personnelles et vos paramètres.</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Profile Info Card --}}
        <div class="col-md-4">
            <div class="app-card p-4 text-center">
                <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-circle shadow-lg" 
                     style="width: 120px; height: 120px; font-size: 3rem; font-family: 'Playfair Display', serif;">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1">{{ Auth::user()->name }}</h4>
                <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                <div class="d-flex justify-content-center gap-2 mb-4">
                    @switch(Auth::user()->role)
                        @case('admin')
                            <span class="badge bg-dark rounded-pill px-4 py-2">{{ __('messages.admin') }}</span>
                            @break
                        @case('medecin')
                            <span class="badge bg-secondary rounded-pill px-4 py-2">{{ __('messages.doctor') }}</span>
                            @break
                        @default
                            <span class="badge border border-dark text-dark rounded-pill px-4 py-2">{{ __('messages.patient') }}</span>
                    @endswitch
                </div>
                <div class="text-start border-top pt-3">
                    <p class="small mb-1 text-muted">{{ __('messages.member_since') }}</p>
                    <p class="fw-semibold">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Settings / Form Card --}}
        <div class="col-md-8">
            <div class="app-card p-4 h-100">
                <h5 class="fw-bold mb-4" style="font-family:'Playfair Display',serif;">Informations du compte</h5>
                
                <form action="#" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('messages.email') }}</label>
                            <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                        </div>
                        <div class="col-md-12">
                            <div class="alert alert-light border rounded-3 small">
                                <i class="bi bi-info-circle me-2"></i> Les modifications du profil sont actuellement restreintes par l'administrateur.
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-4" style="font-family:'Playfair Display',serif;">{{ __('messages.language') }}</h5>
                    <div class="d-flex gap-3">
                        <a href="{{ url('lang/fr') }}" class="btn {{ app()->getLocale() == 'fr' ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill flex-fill">
                            🇫🇷 {{ __('messages.lang_fr') }}
                        </a>
                        <a href="{{ url('lang/en') }}" class="btn {{ app()->getLocale() == 'en' ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill flex-fill">
                            🇬🇧 {{ __('messages.lang_en') }}
                        </a>
                        <a href="{{ url('lang/es') }}" class="btn {{ app()->getLocale() == 'es' ? 'btn-dark' : 'btn-outline-dark' }} rounded-pill flex-fill">
                            🇪🇸 {{ __('messages.lang_es') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
