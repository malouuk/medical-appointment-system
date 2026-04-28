@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 600px;">
    <div class="card app-card">
        <div class="card-body">
            <h1 class="h4 page-title mb-3">Vérification de l'email</h1>

            <p class="text-muted">Merci de vérifier votre adresse email avant de continuer.</p>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success">Un nouveau lien de vérification a été envoyé.</div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-send me-1"></i>Renvoyer le lien
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
