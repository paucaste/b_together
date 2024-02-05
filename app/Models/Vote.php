<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'survey_id',
        'response_id',
    ];

    // Cada voto pertenece a un usuario
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Cada voto pertenece a una encuesta
    public function survey() {
        return $this->belongsTo(Survey::class);
    }

    // Cada voto pertenece a una respuesta de encuesta
    public function surveyResponse() {
        return $this->belongsTo(SurveyResponse::class, 'response_id');
    }
}
