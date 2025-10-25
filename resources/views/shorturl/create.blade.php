@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Créer une URL courte</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="POST" action="{{ route('shorturl.store') }}">
        @csrf
        <div class="mb-3">
            <label for="original_url" class="form-label">URL à raccourcir</label>
            <input type="url" name="original_url" id="original_url" class="form-control" required>
            @error('original_url')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Raccourcir</button>
    </form>
</div>
@endsection
