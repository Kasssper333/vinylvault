<?php

use App\Http\Controllers\BasicController;
use App\Http\Controllers\AlbumController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BasicController::class, 'index'])->name('home');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/create', [AlbumController::class, 'create'])->name('create');
    Route::post('/store', [AlbumController::class, 'store'])->name('store');
    
    Route::get('/edit', [AlbumController::class, 'edit'])->name('edit');
    Route::put('/update', [AlbumController::class, 'update'])->name('update');
    
    Route::get('/delete', [AlbumController::class, 'deleteForm'])->name('delete');
    Route::delete('/delete', [AlbumController::class, 'delete'])->name('delete.submit');

    Route::post('/search', [AlbumController::class, 'search'])->name('search');
    Route::post('/find-album', [AlbumController::class, 'findAlbum'])->name('find.album');
});
