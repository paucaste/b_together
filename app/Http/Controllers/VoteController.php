<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Vote;
use Illuminate\Support\Facades\Log;


class VoteController extends Controller
{
    public function store(Request $request, Survey $survey)
{
    // Validar la solicitud...

    try {
        $vote = new Vote;
        $vote->user_id = $request->user()->id; // Obtenemos el id del usuario autenticado
        $vote->survey_id = $survey->id; // Obtenemos el id de la encuesta desde la ruta
        $vote->response_id = $request->input('response_id'); // Obtenemos el id de la respuesta desde el cuerpo del request

        $vote->save();

        return response()->json(['vote' => $vote], 201);
    } catch (\Illuminate\Database\QueryException $e) {
        // Aquí puedes personalizar tu mensaje de error. Por ejemplo:
        if ($e->getCode() == 23000) { // Código de error de duplicado
            return response()->json(['error' => 'Ya has votado en esta encuesta'], 409);
        } else {
            // Si ocurre cualquier otro error, simplemente devuelves el mensaje de error original
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}


    /*public function store(Request $request, Survey $survey)
{
    // Validar la solicitud...

    // Verifica si ya existe un voto para el usuario y la encuesta
    $existingVote = Vote::where('user_id', $request->user()->id)
        ->where('survey_id', $survey->id)
        ->first();

    // Si el voto ya existe, regresa un mensaje de error
    if($existingVote) {
        return response()->json(['message' => 'You have already voted in this survey'], 403);
    }

    $vote = new Vote;
    $vote->user_id = $request->user()->id; // Obtenemos el id del usuario autenticado
    $vote->survey_id = $survey->id; // Obtenemos el id de la encuesta desde la ruta
    $vote->response_id = $request->input('response_id'); // Obtenemos el id de la respuesta desde el cuerpo del request

    $vote->save();

    return response()->json(['vote' => $vote], 201);
}*/



    // buscar els vots de l'usuari a la bd
    public function getUserVote($survey_id, $user_id)
{
    try{
        error_log("Received request for survey $survey_id and user $user_id");
        $vote = Vote::where('user_id', $user_id)->where('survey_id', $survey_id)->first();
        Log::info('User ID: ' . $user_id);
    Log::info('Survey ID: ' . $survey_id);
    
        if($vote){
            Log::info('Vote found: ' . json_encode($vote));
            return response()->json($vote, 200);
        } else {
            Log::info('No vote found');
            return response()->json(null, 404);
        }
    }catch (\Exception $e) {
        // Algo salió mal. Devuelve un mensaje de error con la excepción.
        Log::error("Error getting user vote: " . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
    
}



}
