@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 560px;">
    <div class="card app-card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h1 class="h4 page-title mb-1">Mot de passe oublié</h1>
                <p class="text-muted mb-0">Recevez un lien de réinitialisation par email.</p>
            </div>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-envelope-paper me-1"></i>Envoyer le lien
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
