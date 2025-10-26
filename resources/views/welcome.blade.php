@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center min-vh-100">
    <div class="text-center">
        <h1 class="display-3 fw-bold mb-4 text-primary">Bienvenue sur URL Shortener !</h1>
        @if (Route::has('login'))
            @auth
                <a href="{{ route('shorturl.create') }}" class="btn btn-success btn-lg">Ajouter une URL</a>
                <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary btn-lg ms-2">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-lg">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg ms-2">Inscription</a>
            @endauth
        @endif
    </div>
</div>
@endsection