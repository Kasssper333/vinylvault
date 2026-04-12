<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Albums extends Model
{
     public $timestamps = false;
    use HasFactory, SoftDeletes;
    
    // 👇 ДОБАВЬ ЭТОТ МАССИВ
    protected $fillable = [
        'title',
        'artist',
        'description',
        'cover_url',
        'lastfm_url',
        'created_by',
        'updated_by',
    ];
    
    // Если хочешь защитить все поля (обратное)
    // protected $guarded = [];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    public function logs()
    {
        return $this->hasMany(AlbumLog::class);
    }
}

