@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: calc(100vh - 80px);">
        <!-- Form Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-sm-5 bg-white order-2 order-lg-1">
            <div class="w-100" style="max-width: 450px;">
                <div class="text-center mb-5">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-circle" style="width: 60px; height: 60px;">
                        <i class="bi bi-heart-pulse fs-3"></i>
                    </div>
                    <h1 class="h2" style="font-family: 'Playfair Display', serif; font-weight: 700;">Rejoignez-nous</h1>
                    <p class="text-muted">Créez votre dossier patient en quelques instants.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-dark border-0 bg-light text-dark rounded-3 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" name="name" class="form-control" id="floatingName" value="{{ old('name') }}" placeholder="Jean Dupont" required>
                        <label for="floatingName">Nom Complet</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingEmail" value="{{ old('email') }}" placeholder="name@example.com" required>
                        <label for="floatingEmail">Adresse Email</label>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                                <label for="floatingPassword">Mot de passe</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-4">
                                <input type="password" name="password_confirmation" class="form-control" id="floatingPasswordConf" placeholder="Confirm" required>
                                <label for="floatingPasswordConf">Confirmer</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-3 mb-4 rounded-pill fw-bold text-uppercase" style="letter-spacing: 1px;">
                        Créer le compte <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center text-muted">
                    Déjà patient ? <a href="{{ route('login') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark pb-1">Se connecter</a>
                </div>
            </div>
        </div>

        <!-- Image Section -->
        <div class="col-lg-6 d-none d-lg-flex position-relative order-1 order-lg-2">
            <div class="position-absolute w-100 h-100" style="
                background-image: url('https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=2000&auto=format&fit=crop');
                background-size: cover; 
                background-position: center; 
                filter: grayscale(100%) contrast(1.1) brightness(0.85);">
            </div>
            <div class="position-absolute w-100 h-100" style="background: linear-gradient(to left, rgba(0,0,0,0.7), rgba(0,0,0,0.3));"></div>
            <div class="position-relative z-index-1 text-white p-5 d-flex flex-column justify-content-center text-end w-100">
                <h2 class="display-4" style="font-family: 'Playfair Display', serif;">Prenez soin de vous.</h2>
                <p class="lead mt-3 ms-auto" style="font-weight: 300; max-width: 80%;">Des spécialistes à votre écoute, une prise en charge premium et un suivi irréprochable.</p>
            </div>
        </div>
    </div>
</div>
@endsection
