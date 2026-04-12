<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Services\LastFmService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportAlbumsCommand extends Command
{
    protected $signature = 'albums:import {limit=20}';
    protected $description = 'Import top albums from Last.fm';
    
    public function handle(LastFmService $lastfm)
    {
        $limit = $this->argument('limit');
        $this->info("Importing {$limit} top albums from Last.fm...");
        
        // Получаем популярные альбомы
        $albums = $lastfm->getTopAlbums($limit);
        
        if (empty($albums)) {
            $this->error('Failed to fetch albums from Last.fm API');
            $this->warn('Check your API key or try again later');
            return 1;
        }
        
        $imported = 0;
        $skipped = 0;
        
        foreach ($albums as $albumData) {
            // Получаем полную информацию об альбоме
            $info = $lastfm->getAlbumInfo(
                $albumData['artist']['name'],
                $albumData['name']
            );
            
            if (!$info) {
                $skipped++;
                continue;
            }
            
            // Проверяем, нет ли уже такого альбома
            $exists = Album::where('title', $info['title'])
                ->where('artist', $info['artist'])
                ->exists();
            
            if ($exists) {
                $skipped++;
                $this->line("Skipped: {$info['artist']} - {$info['title']} (already exists)");
                continue;
            }
            
            // Сохраняем альбом
            Album::create([
                'title' => $info['title'],
                'artist' => $info['artist'],
                'cover_url' => $info['cover_url'],
                'lastfm_url' => $info['lastfm_url'],
                'description' => $info['description'],
                'created_by' => 1,  // ID первого пользователя
                'updated_by' => 1,
            ]);
            
            $imported++;
            $this->info("Imported: {$info['artist']} - {$info['title']}");
        }
        
        $this->newLine();
        $this->info("✅ Import completed!");
        $this->info("📀 Imported: {$imported}");
        $this->info("⏭️ Skipped: {$skipped}");
        
        return 0;
    }
}