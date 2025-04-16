<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = "tag";
    protected $primaryKey = "id_tag";
    protected $fillable = [
        'nama_tag',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function artikels()
    {
        return $this->belongsToMany(Artikel::class, 'tag_artikel', 'id_tag', 'id_artikel');
    }
}
