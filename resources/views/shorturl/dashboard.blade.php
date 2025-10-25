@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tableau de bord de mes URLs courtes</h2>
    <a href="{{ route('shorturl.create') }}" class="btn btn-success mb-3">Ajouter une URL</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>URL originale</th>
                <th>URL courte</th>
                <th>Utilisations</th>
            </tr>
        </thead>
        <tbody>
            @foreach($urls as $url)
                <tr>
                    <td>{{ $url->original_url }}</td>
                    <td><a href="{{ url('/' . $url->short_url) }}" target="_blank">{{ url('/' . $url->short_url) }}</a></td>
                    <td>{{ $url->usage_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $urls->links() }}
    </div>
</div>
@endsection
