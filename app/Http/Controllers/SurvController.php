<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Storage;

class SurvController extends Controller
{
    // get all surveys
    public function index()
    {
        // get all surveys with user and responses relation
        $surveys = Survey::orderBy('created_at', 'desc')
            ->with('user:id,name')
            ->with(['responses' => function ($query) {
                $query->withCount('votes as votes_count'); 
            }])
            ->withCount('votes') // add total votes count
            ->get();

        // calculate vote percentages
        $this->calculateVotePercentages($surveys);

        // return the surveys with the vote percentages
        return response(['surveys' => $surveys], 200);
    }

    // obtenir totes les encuestas que he creat com a usuari organitzacio
    public function mySurveys()
{
    // get surveys of the currently logged-in user
    $surveys = Survey::where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->with('user:id,name')
        ->with(['responses' => function ($query) {
            $query->withCount('votes as votes_count'); 
        }])
        ->withCount('votes') // add total votes count
        ->get();

    // calculate vote percentages
    $this->calculateVotePercentages($surveys);

    // return the surveys with the vote percentages
    return response(['surveys' => $surveys], 200);
}


public function myInactiveSurveys()
{
    // get inactive surveys of the currently logged-in user
    $surveys = Survey::where('user_id', auth()->id())
        ->where('active', 0)  // <-- aquí está la nueva condición para obtener solo las encuestas inactivas
        ->orderBy('created_at', 'desc')
        ->with('user:id,name')
        ->with(['responses' => function ($query) {
            $query->withCount('votes as votes_count'); 
        }])
        ->withCount('votes') // add total votes count
        ->get();

    // calculate vote percentages
    $this->calculateVotePercentages($surveys);

    // return the surveys with the vote percentages
    return response(['surveys' => $surveys], 200);
}

public function myActiveSurveys()
{
    // get inactive surveys of the currently logged-in user
    $surveys = Survey::where('user_id', auth()->id())
        ->where('active', 1)  // <-- aquí está la nueva condición para obtener solo las encuestas inactivas
        ->orderBy('created_at', 'desc')
        ->with('user:id,name')
        ->with(['responses' => function ($query) {
            $query->withCount('votes as votes_count'); 
        }])
        ->withCount('votes') // add total votes count
        ->with('files') // agrega els arxius
        ->get();
        
    // calculate vote percentages
    $this->calculateVotePercentages($surveys);

    // return the surveys with the vote percentages
    return response(['surveys' => $surveys], 200);
}


public function surveysVotant() {
    // Obtener el municipio del usuario votant actual
    $currentMunicipioId = auth()->user()->municipio_id;

    // Obtener las encuestas de los usuarios con rol "organitzacio" en el mismo municipio
    $surveys = Survey::whereHas('user', function ($query) use ($currentMunicipioId) {
            $query->where('role', 'organitzacio')
                  ->where('municipio_id', $currentMunicipioId);
        })
        ->orderBy('created_at', 'desc')
        ->with('user:id,name')
        ->with(['responses' => function ($query) {
            $query->withCount('votes as votes_count'); 
        }])
        ->withCount('votes') // añadir el total de votos
        ->with('files') // agrega els arxius
        ->get();

    // Calcular los porcentajes de votos (asumiendo que ya tienes esa función)
    $this->calculateVotePercentages($surveys);

    // Retornar las encuestas con los porcentajes de votos
    return response(['surveys' => $surveys], 200);
}

    // get single survey
    public function show($id)
    {
        return response([
            'surveys' => Survey::where('id', $id)
                ->with('user:id,name')
                ->with('responses')
                ->withCount('votes') // añadir el total de votos
                ->get()  
        ], 200);
    }

    // create a survey
    public function store(Request $request)
    {
        // validem que s'han proporcionat title, description i responses
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'responses' => 'required|array|min:1',
            'start_date'  => 'required|date',
            'end_date'    => [
             'required',
             'date',
             'after:start_date',
            ],
        ]);
        // creem la encuesta
        $survey = Survey::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
        ]);
        // itarem sobre les respostes i creem un nou registre survey response per cada una
        foreach ($request->responses as $response) {
            SurveyResponse::create([
                'survey_id' => $survey->id,
                'response' => $response,
            ]);
        }

        return response()->json([
            'message' => 'Survey created successfully.',
            'survey_id' => $survey->id
        ], 200);
        
    }

    // delete survey
    public function destroy($id)
{
    $survey = Survey::findOrFail($id);

    // 1. Obtener todos los archivos asociados a la encuesta
    $files = $survey->files;

    foreach ($files as $file) {
        // 2. Eliminar el archivo del sistema de archivos
        Storage::disk('public')->delete($file->path);
        
        // 3. Eliminar el registro del archivo de la base de datos
        $file->delete();
    }

    // Las respuestas se eliminarán debido al setup de eliminación en cascada en la migración
    $survey->delete();

    return response()->json(['message' => 'Survey and associated files deleted successfully.'], 200);
}

private function calculateVotePercentages($surveys)
    {
        foreach ($surveys as $survey) {
            $totalVotes = $survey->votes_count;
            foreach ($survey->responses as $response) {
                $responseVotes = $response->votes_count;
                $percentage = ($totalVotes > 0) ? ($responseVotes / $totalVotes) * 100 : 0;
                $response['percentage'] = $percentage;
            }
        }
    }


}



