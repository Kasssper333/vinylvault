@extends('layouts/main')

@section('header-title')
    VinylVault
@endsection

@section('content')
    <div class="main-container">
        <h1 class="title title1">Music Albums Collection</h1>
        
        @auth
        <div class="album-actions">
            <a href="{{ route('create') }}" class="btn1 btn-add">➕ Add New Album</a>
            <a href="{{ route('edit') }}" class="btn1 btn-edit">✏️ Edit Album</a>
            <a href="{{ route('delete') }}" class="btn1 btn-delete">🗑️ Delete Album</a>
        </div>
        @endauth
        
        @if($albums->count() > 0)
            <div class="albums-grid">
                @foreach($albums as $album)
                <div class="album-card">
                    @if($album->cover_url)
                    <img src="{{ $album->cover_url }}" class="album-cover" alt="{{ $album->title }}">
                    @else
                    <div class="album-cover placeholder">🎵 No cover</div>
                    @endif
                    
                    <div class="album-info">
                        <h3 class="album-title">{{ $album->title }}</h3>
                        <p class="album-artist">{{ $album->artist }}</p>
                        <p class="album-description">{{ Str::limit($album->description, 150) }}</p>
                        
                        @if($album->lastfm_url)
                        <a href="{{ $album->lastfm_url }}" target="_blank" class="lastfm-link">🔗 View on Last.fm</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="pagination">
                {{ $albums->links() }}
            </div>
        @else
            <div class="empty-message">
                <p class="empty-text">🎵 You don't have any albums yet.</p>
            </div>
        @endif
    </div>
@endsection