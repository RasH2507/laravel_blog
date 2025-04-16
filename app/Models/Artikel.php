<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    protected $table = "artikel";
    protected $primaryKey = "id_artikel";
    protected $fillable = [
        'id_user',
        'id_game',
        'judul',
        'konten',
        'image',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function game()
    {
        return $this->belongsTo(Game::class, 'id_game');
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_artikel', 'id_artikel', 'id_tag');
    }
    public function sections()
    {
        return $this->hasMany(ArtikelSection::class, 'id_artikel');
    }
    public function comments()
    {
        return $this->hasMany(Komentar::class, 'id_artikel', 'id_artikel');
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'id_artikel', 'id_artikel');
    }

    public function isLikedByUser()
    {
        if (Auth::check()) {
            return $this->likes()->where('id_user', Auth::id())->exists();
        }
        return false;
    }
}
