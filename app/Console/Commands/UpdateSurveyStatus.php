<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;

class UpdateSurveyStatus extends Command
{
    protected $signature = 'surveys:update-status';
    protected $description = 'Update the status of surveys based on their end_date';
    
    public function handle()
    {
        $surveys = Survey::where('active', 1)
                    ->where('end_date', '<', now())
                    ->update(['active' => 0]);
    
        $this->info("Surveys updated successfully.");
    }
}
