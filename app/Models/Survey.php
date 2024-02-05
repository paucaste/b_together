<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;

class Survey extends Model
{
    protected $fillable = ['user_id', 'title', 'description', 'start_date', 'end_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function isActive()
    {
        $now = now();

        if ($this->start_date && $this->start_date->gt($now)) {
            return false;  // La encuesta aún no ha empezado
        }

        if ($this->end_date && $this->end_date->lt($now)) {
            return false;  // La encuesta ya ha finalizado
        }

        return true;
    }

    public function files()
{
    return $this->hasMany(File::class);
}


}
