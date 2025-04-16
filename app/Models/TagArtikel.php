<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagArtikel extends Model
{
    protected $table = "tag_artikel";
    protected $primaryKey = "id_tag_artikel";
    protected $fillable = [
        'id_artikel',
        'id_tag',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class, 'id_artikel');
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'id_tag');
    }
}
