<?php

namespace App\Http\Controllers;

use App\Models\Albums;
use App\Models\AlbumLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AlbumController extends Controller
{

    // Форма создания
    public function create()
    {
        return view('static.create');
    }

    // Сохранение нового альбома
    public function store(Request $request)
    {
        $album = Albums::create([
            'title' => $request->title,
            'artist' => $request->artist,
            'description' => $request->description,
            'cover_url' => $request->cover_url,
            'lastfm_url' => $request->lastfm_url,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        \Log::info('Created album ID: ' . $album->id);
        
        \DB::table('album-logs')->insert([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_data' => json_encode($album->toArray(), JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'Album created!');

        }

    // Форма редактирования
    public function edit()
    {
        return view('static.edit'); 
    }



    // Обновление
    public function update(Request $request)
    {
        $album = Albums::find($request->album_id);
        
        if (!$album) {
            return redirect()->route('home')->with('error', 'Album not found');
        }
        
        // Сохраняем старые данные
        $oldData = $album->toArray();
        
        // Обновляем
        $album->update([
            'artist' => $request->artist,
            'title' => $request->title,
            'description' => $request->description,
            'cover_url' => $request->cover_url,
            'lastfm_url' => $request->lastfm_url,
            'updated_by' => Auth::id(),
        ]);
        
        // Логируем
        \DB::table('album-logs')->insert([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'updated',
            'old_data' => json_encode($oldData, JSON_UNESCAPED_UNICODE),
            'new_data' => json_encode($album->toArray(), JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('home')->with('success', 'Album updated!');
    }

    // Страница удаления
    public function deleteForm()
    {
        return view('static.delete');
    }

    // Обработка удаления
    public function delete(Request $request)
    {
        $albumId = $request->album_id;
        
        if (!$albumId) {
            return redirect()->route('home')->with('error', 'Album ID not provided');
        }
        
        $album = Albums::find($albumId);
        
        if (!$album) {
            return redirect()->route('home')->with('error', 'Album not found');
        }
        
        // Сохраняем данные для лога
        $albumData = $album->toArray();
        $albumTitle = $album->title;
        
        \DB::table('album-logs')->insert([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => 'deleted',
            'old_data' => json_encode($albumData, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $album->forceDelete();
        
        return redirect()->route('home')->with('success', 'Album "' . $albumTitle . '" deleted!');
    }

    public function findAlbum(Request $request)
    {
        $title = $request->title;
        
        $album = Albums::where('title', 'LIKE', "%$title%")->first();
        
        if ($album) {
            return response()->json([
                'success' => true,
                'album' => $album
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Album not found'
        ]);
    }


    // Поиск в Last.fm API
    public function search(Request $request)
    {
        $artist = $request->artist;
        $album = $request->album;
        $apiKey = env('LASTFM_API_KEY');

        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data['album'])) {
                $albumInfo = $data['album'];
                
                $coverUrl = '';
                foreach ($albumInfo['image'] as $image) {
                    if ($image['size'] == 'extralarge') {
                        $coverUrl = $image['#text'];
                        break;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'description' => strip_tags($albumInfo['wiki']['summary'] ?? ''),
                    'cover_url' => $coverUrl,
                    'lastfm_url' => $albumInfo['url'] ?? '',
                ]);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Альбом не найден'
        ]);
    }
}