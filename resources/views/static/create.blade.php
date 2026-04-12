@extends('layouts/main')

@section('header-title')
    VinylVault
@endsection

@section('content')
    <div class="main-container">
        <h1 class="title">Create New Album</h1>
        <div class="main-blok">
            <form action="{{ route('store') }}" method="POST">
                @csrf
                
                <div class="blok-input">
                    <label class="form-label">Artist *</label>
                    <input class="form-input" type="text" id="artist" name="artist" required>
                </div>

                <div class="blok-input">
                    <label class="form-label">Album Title *</label>
                    <input class="form-input" type="text" id="title" name="title" required>
                </div>

                <button type="button" id="searchBtn" class="btn btn-search">🔍 Search on Last.fm</button>

                <div class="blok-input">
                    <label class="form-label">Cover Image URL</label>
                    <input class="form-input" type="text" id="cover_url" name="cover_url">
                </div>

                <div class="blok-input">
                    <label class="form-label">Description</label>
                    <textarea class="description form-input" id="description" name="description" rows="5"></textarea>
                </div>

                <input type="hidden" id="lastfm_url" name="lastfm_url">

                <button type="submit" class="btn">💾 Save Album</button>
                <a href="{{ route('home') }}" class="btn btn-cancel">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @vite('resources/js/pages/create.js')
@endsection