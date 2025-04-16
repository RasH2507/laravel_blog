<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtikelSection extends Model
{
    protected $table = "artikel_section";
    protected $primaryKey = "id_section";
    protected $fillable = [
        'id_artikel',
        'sub_judul',
        'media_type',
        'media_content',
        'deskripsi',
        'urutan',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }
}
