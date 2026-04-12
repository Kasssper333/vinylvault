@extends('layouts/main')

@section('header-title')
    VinylVault
@endsection

@section('content')
    <div class="main-container">
        <h1 class="title">Delete Album</h1>
        <div class="main-blok">
            
            <div class="search-block">
                <div class="search-row">
                    <input class="form-input" type="text" id="search_title" placeholder="Search album by title..." class="search-input">
                    <button type="button" id="searchBtn" class="btn btn-search" data-url="{{ route('find.album') }}">🔍 Search</button>
                </div>
                <div id="searchMessage" class="search-message"></div>
            </div>
            
            <hr>
            
            <form id="deleteForm" action="{{ route('delete.submit') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <input type="hidden" id="album_id" name="album_id">
                
                <div class="blok-input">
                    <label class="form-label">Artist</label>
                    <input class="form-input" type="text" id="artist" name="artist">
                </div>

                <div class="blok-input">
                    <label class="form-label">Album Title</label>
                    <input class="form-input" type="text" id="title" name="title" >
                </div>

                <button type="submit" class="btn btn-delete" onclick="return confirm('Delete this album?')">🗑️ Delete Album</button>
                <a href="{{ route('home') }}" class="btn btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/pages/delete.js')
@endsection