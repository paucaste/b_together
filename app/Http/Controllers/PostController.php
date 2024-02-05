<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\PostsFile;

class PostController extends Controller
{
    /// CREAR POST
    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts_images', 'public');
        }

        $post = new Post([
            'title' => $request->title,
            'body' => $request->body,
            'image' => $imagePath, // Agregar la ruta de la imagen al modelo de Post si tienes un campo para esto.
            'user_id' => auth()->user()->id,
        ]);

        if ($post->save()) {
            return response()->json(['message' => 'Post creado exitosamente', 'post' => $post], 201);
        } else {
            return response()->json(['message' => 'Error al crear el post'], 500);
        }
    }

    /// OBTENIR POST ESPECIFIC PER usuari

    public function showUserPosts() {
        // Obtiene todos los posts creados por ese usuario junto con información del usuario y ordenados por los más recientes primero
        $posts = Post::with('user')
                     ->where('user_id', auth()->id())
                     ->orderBy('created_at', 'desc')
                     ->get();
    
        if ($posts->isEmpty()) {
            return response()->json(['message' => 'No se encontraron posts para este usuario'], 404);
        }
    
        return response()->json($posts);
    }
    
    /// obtenir un post per id

    /*public function show($id) {
        $post = Post::with('user')->find($id);
    
        if (!$post) {
            return response()->json(['message' => 'No se encontró el post'], 404);
        }
    
        return response()->json($post);
    }*/

    public function show($id) {
        $post = Post::with(['user', 'files'])->find($id);
    
        if (!$post) {
            return response()->json(['message' => 'No se encontró el post'], 404);
        }
    
        return response()->json($post);
    }

    /// OBTENIR TOTS ELS POSTS
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    /// UPDATE POST
    public function update(Request $request, $id)
{
    try {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Post no encontrado'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'body' => 'sometimes|required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts_images', 'public');
            $post->image = $imagePath;
        }

        $post->title = $request->input('title', $post->title);
        $post->body = $request->input('body', $post->body);

        if (!$post->save()) {
            \Log::error('El post no se actualizó.');
            return response()->json(['message' => 'Error al actualizar el post'], 500);
        }

        return response()->json(['message' => 'Post actualizado exitosamente', 'post' => $post]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Personalizar el error de validación si es necesario
        return response()->json(['message' => 'Errores de validación', 'errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        // Capturar cualquier otra excepción
        return response()->json(['message' => 'Error del servidor', 'error' => $e->getMessage()], 500);
    }
}


    /// DESTROY POST
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if (!$post) {
            return response()->json(['message' => 'Post no encontrado'], 404);
        }

        // Si el post tiene una imagen asociada, eliminarla
        if ($post->image) {
         Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return response()->json(['message' => 'Post eliminado exitosamente']);
    }

    








}
