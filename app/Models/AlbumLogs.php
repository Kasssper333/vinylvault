<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumLogs extends Model
{
    protected $table = 'album-logs';
    
    protected $fillable = [
        'album_id',   
        'user_id',
        'action',
        'old_data',
        'new_data',
    ];
    
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
    
    public $timestamps = true;
    
    public function album()
    {
        return $this->belongsTo(Albums::class, 'album_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}