<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Komentar extends Model
{
    protected $table = "komentar";
    protected $primaryKey = "id_komentar";
    protected $fillable = [
        'id_artikel',
        'id_user',
        'isi_komentar',
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
