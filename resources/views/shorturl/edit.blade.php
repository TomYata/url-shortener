@extends('layouts.app')

@section('content')
<div class="container mt-5" style="max-width: 500px;">
    <h2 class="text-center mb-4">Modifier l’URL courte</h2>
    <form method="POST" action="{{ route('shorturl.update', $url->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="original_url" class="form-label">URL à raccourcir</label>
            <input type="url" name="original_url" id="original_url" class="form-control" value="{{ old('original_url', $url->original_url) }}" required>
            @error('original_url')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">Annuler</a>
            <button type="submit" class="btn btn-success">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
