<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LastFmService
{
    protected $apiKey;
    protected $baseUrl = 'https://ws.audioscrobbler.com/2.0/';
    
    public function __construct()
    {
        $this->apiKey = config('services.lastfm.api_key');
    }
    
    /**
     * Поиск альбомов по исполнителю
     */
    public function searchAlbums($artist, $limit = 10)
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'method' => 'artist.gettopalbums',
            'artist' => $artist,
            'api_key' => $this->apiKey,
            'format' => 'json',
            'limit' => $limit,
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return $data['topalbums']['album'] ?? [];
        }
        
        Log::error('Last.fm API error: ' . $response->body());
        return [];
    }
    
    /**
     * Получение информации об одном альбоме
     */
    public function getAlbumInfo($artist, $album)
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'method' => 'album.getinfo',
            'artist' => $artist,
            'album' => $album,
            'api_key' => $this->apiKey,
            'format' => 'json',
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['album'])) {
                return [
                    'title' => $data['album']['name'],
                    'artist' => $data['album']['artist'],
                    'cover_url' => $this->getLargestImage($data['album']['image']),
                    'lastfm_url' => $data['album']['url'],
                    'description' => $data['album']['wiki']['summary'] ?? '',
                ];
            }
        }
        
        return null;
    }
    
    /**
     * Получение популярных альбомов со всего мира
     */
    public function getTopAlbums($limit = 20)
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'method' => 'chart.gettopalbums',
            'api_key' => $this->apiKey,
            'format' => 'json',
            'limit' => $limit,
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            return $data['albums']['album'] ?? [];
        }
        
        Log::error('Last.fm API error: ' . $response->body());
        return [];
    }
    
    /**
     * Получение самой большой обложки
     */
    protected function getLargestImage(array $images): ?string
    {
        $sizes = ['extralarge', 'mega', 'large', 'medium', 'small'];
        
        foreach ($sizes as $size) {
            foreach ($images as $image) {
                if (isset($image['size']) && $image['size'] === $size && !empty($image['#text'])) {
                    return $image['#text'];
                }
            }
        }
        
        return null;
    }
}