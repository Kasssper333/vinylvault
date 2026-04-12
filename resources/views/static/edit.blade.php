@extends('layouts/main')

@section('header-title')
    VinylVault
@endsection

@section('content')
    <div class="main-container">
        <h1 class="title">Edit Album</h1>
        <div class="main-blok">
            
            <div class="search-block">
                <div class="search-row">
                    <input class="form-input" type="text" id="search_title" placeholder="Search album by title..." class="search-input">
                    <button type="button" id="searchBtn" class="btn btn-search" data-url="{{ route('find.album') }}">🔍 Search</button>
                </div>
                <div id="searchMessage" class="search-message"></div>
            </div>
            
            <hr>
            
            <form action="{{ route('update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <input type="hidden" id="album_id" name="album_id">
                
                <div class="blok-input">
                    <label class="form-label">Artist *</label>
                    <input class="form-input" type="text" id="artist" name="artist" required>
                </div>

                <div class="blok-input">
                    <label class="form-label">Album Title *</label>
                    <input class="form-input" type="text" id="title" name="title" required>
                </div>

                <div class="blok-input">
                    <label class="form-label">Cover Image URL</label>
                    <input class="form-input" type="text" id="cover_url" name="cover_url" placeholder="https://...">
                </div>

                <div class="blok-input">
                    <label class="form-label">Description</label>
                    <textarea class="description form-input" id="description" name="description" rows="5"></textarea>
                </div>

                <input type="hidden" id="lastfm_url" name="lastfm_url">

                <button type="submit" class="btn">💾 Save Changes</button>
                <a href="{{ route('home') }}" class="btn btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/pages/edit.js')
@endsection