@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 560px;">
    <div class="card app-card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h1 class="h4 page-title mb-1">Réinitialiser le mot de passe</h1>
                <p class="text-muted mb-0">Choisissez un nouveau mot de passe sécurisé.</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $request->email) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-shield-lock me-1"></i>Réinitialiser
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
