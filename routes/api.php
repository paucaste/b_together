<?php

use Illuminate\Http\Request; // proporciona una interfaz orie. obj. per treballar amb dades de solicitut HTTP
use Illuminate\Support\Facades\Route; // permet definir les rutes de l'app
use App\Http\Controllers\AuthController; // controlador per administrar les solicituts HTTP
use App\Http\Controllers\SurvController; 
use App\Http\Controllers\VoteController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PostController;


//public routes
Route::post('/register', [AuthController::class, 'register']); // Defineix una ruta POST a /register que serà administrada pel metodes 'register' dintre del AuthController
Route::post('/login', [AuthController::class, 'login']);

//Municipis
Route::get('/municipios', [MunicipioController::class, 'showAllMunicipios']); // mostra tots els municipis
Route::get('/search', [MunicipioController::class, 'search']); // busca el municipi mentres escrius

//Protected routes
// creem un grup de rutes que seran protegides pel middleware 'auth:sanctum'. Nomes els usuaris autentificats amb el token podran accedir a aquestes rutes del grup
Route::group(['middleware' => ['auth:sanctum']], function(){
    //User
    Route::get('/user', [AuthController::class, 'user']); // agafar user details
    Route::post('/logout', [AuthController::class, 'logout']);

    // Encuesta
    Route::get('/surveys', [SurvController::class, 'index']); // all surveys
    Route::post('/surveys', [SurvController::class, 'store']); // create survey
    Route::get('/surveys/{id}', [SurvController::class, 'show']); // get single survey
    //Route::put('/surveys/{id}', [SurvController::class, 'update']); // update survey
    Route::delete('/surveys/{id}', [SurvController::class, 'destroy']); // delete survey
    Route::get('/mysurveys', [SurvController::class, 'mySurveys']); // agafar les encuestas que ha creat una organitzacio
    Route::get('/myinactivesurveys', [SurvController::class, 'myInactiveSurveys']); // agafar les encuestas inactives que ha creat una organitzacio
    Route::get('/myactivesurveys', [SurvController::class, 'myActiveSurveys']); // agafar les encuestas actives que ha creat una organitzacio
    Route::get('/surveysvotants', [SurvController::class, 'surveysVotant']); // agafar les encuestas que pot votar el votant del municipi

    //Vots
    Route::post('/surveys/{survey}/votes', [VoteController::class, 'store']);
    Route::get('/surveys/{id}/with-percentages', [SurveyController::class, 'getSurveyWithPercentages']);
    Route::get('/surveys/{survey_id}/votes/user/{user_id}', [VoteController::class, 'getUserVote']);     // Obtener el voto de un usuario para una encuesta

    //Posts
    Route::post('/posts', [PostController::class, 'store']); // crear post
    Route::get('/myposts', [PostController::class, 'showUserPosts']); // obtener los posts de un usuario
    Route::get('/posts', [PostController::class, 'index']); // obtener todos los posts
    Route::get('/posts/{id}', [PostController::class, 'show']); // obtener un post en concret
    Route::put('/updateposts/{id}', [PostController::class, 'update']); // actualizar post
    Route::delete('/posts/{id}', [PostController::class, 'destroy']); // eliminar post
    //Fitxer survey
    Route::post('/uploadfile', [FileController::class, 'upload']);
    //Fitxer post
    Route::post('/postfile', [FileController::class, 'uploadpostfile']);
});