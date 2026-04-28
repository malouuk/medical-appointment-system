@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0" style="min-height: calc(100vh - 80px);">
        <!-- Image Section -->
        <div class="col-lg-6 d-none d-lg-flex position-relative">
            <div class="position-absolute w-100 h-100" style="
                background-image: url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?q=80&w=2000&auto=format&fit=crop');
                background-size: cover; 
                background-position: center; 
                filter: grayscale(100%) contrast(1.2) brightness(0.9);">
            </div>
            <div class="position-absolute w-100 h-100" style="background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.3));"></div>
            <div class="position-relative z-index-1 text-white p-5 d-flex flex-column justify-content-center">
                <h2 class="display-4" style="font-family: 'Playfair Display', serif;">L'Excellence Médicale.</h2>
                <p class="lead mt-3" style="font-weight: 300;">Connectez-vous pour gérer vos consultations et suivre votre parcours de santé avec la plus haute confidentialité.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-sm-5 bg-white">
            <div class="w-100" style="max-width: 450px;">
                <div class="text-center mb-5">
                    <div class="mb-4 d-inline-flex align-items-center justify-content-center bg-dark text-white rounded-circle" style="width: 60px; height: 60px;">
                        <i class="bi bi-heart-pulse fs-3"></i>
                    </div>
                    <h1 class="h2" style="font-family: 'Playfair Display', serif; font-weight: 700;">Bon retour</h1>
                    <p class="text-muted">Accédez à votre espace sécurisé.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-dark border-0 bg-light text-dark rounded-3 shadow-sm mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="floatingEmail" value="{{ old('email') }}" placeholder="name@example.com" required>
                        <label for="floatingEmail">Adresse Email</label>
                    </div>
                    
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                        <label for="floatingPassword">Mot de passe</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember">Se souvenir de moi</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="text-dark fw-medium text-decoration-none" style="font-size: 0.9rem;">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-3 mb-4 rounded-pill fw-bold text-uppercase" style="letter-spacing: 1px;">
                        Se connecter <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="text-center text-muted">
                    Nouveau patient ? <a href="{{ route('register') }}" class="text-dark fw-bold text-decoration-none border-bottom border-dark pb-1">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
