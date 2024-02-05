<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Survey;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'path', 'type', 'size', 'user_id', 'survey_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
