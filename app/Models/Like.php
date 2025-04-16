<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table = "likes";
    protected $primaryKey = "id_likes";
    protected $fillable = [
        'id_artikel',
        'id_user',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }
}
