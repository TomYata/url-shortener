@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Connexion</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <p class="mt-3">Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
</div>
@endsection
