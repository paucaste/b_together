<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    protected $fillable = ['survey_id', 'response'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'response_id');
    }

    public function getPercentageAttribute()
    {
        $totalVotes = $this->survey->votes->count();

        if ($totalVotes > 0) {
            return ($this->votes->count() / $totalVotes) * 100;
        }

        return 0;
    }
}
