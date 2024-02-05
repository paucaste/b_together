<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostsFile;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'user_id',
        'image' // si estás almacenando la ruta de la imagen en la base de datos
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function files() {
        return $this->hasMany(PostsFile::class);
    }
    
    
}
