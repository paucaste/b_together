<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Post;
use App\Models\PostsFile;
use App\Models\File;
use App\Models\Survey;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File as FileSystem;
use Illuminate\Support\Str;


class FileController extends Controller
{
    // FileController.php
    public function upload(Request $request) {
        $survey_id = $request->input('survey_id');
        $newName = $request->input('new_file_name');

        $existingFiles = File::where('survey_id', $survey_id)->count();
    
        if ($existingFiles >= 3) {
            return response()->json(['error' => 'Ya ha alcanzado el límite de 3 archivos para esta encuesta.'], 400);
        }
    
        $request->validate([
            'file' => 'required|file|max:2048', // 2MB max
        ]);
    
        $file = $request->file('file');
        $originalFilename = $file->getClientOriginalName();
        $path = $file->store('uploads', 'public');
    
        $fileEntry = new File();
        //$fileEntry->name = $originalFilename; // Estableciendo el nombre original del archivo
        $fileEntry->name = $newName;
        $fileEntry->path = $path;
        $fileEntry->user_id = auth()->id();
        $fileEntry->survey_id = $request->input('survey_id');
        $fileEntry->save();
    
        return response()->json(['path' => $path]);
    }

    public function uploadpostfile(Request $request) {
        // Validar el archivo y post_id
        $request->validate([
            'file' => 'required|file|max:2048', // 2MB max
            'post_id' => 'required|integer|exists:posts,id'
        ]);
    
        // Obtener la extensión original del archivo cargado
        $fileExtension = $request->file('file')->getClientOriginalExtension();
        
        // Guardar el archivo en la carpeta 'post_uploads' y obtener el path resultante
        $path = $request->file('file')->store('post_uploads', 'public');
    
        // Obtener solo el nombre del archivo sin la ruta completa ni la extensión
        $storedFileName = basename($path);
    
        // Si el nuevo nombre proporcionado no termina con la extensión del archivo, debemos renombrarlo
        if ($request->input('new_file_name') && !str_ends_with($storedFileName, $fileExtension)) {
            $newName = $request->input('new_file_name') . ".{$fileExtension}";
            Storage::disk('public')->move($path, 'post_uploads/' . $newName);
            $path = 'post_uploads/' . $newName;
        }
    
        // Guardar la entrada en la base de datos
        $postFile = new PostsFile();
        $postFile->name = basename($path); // Esto asegura que solo se almacene el nombre del archivo, no toda la ruta
        $postFile->path = $path;
        $postFile->type = $request->file('file')->getClientMimeType();
        $postFile->size = $request->file('file')->getSize();
        $postFile->user_id = auth()->id();
        $postFile->post_id = $request->input('post_id');
        $postFile->save();
    
        // Devolver la respuesta
        return response()->json(['path' => $path]);
    }
    
    
    
    
    

}
