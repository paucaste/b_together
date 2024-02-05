<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //Register User
    public function register(Request $request){//agafa un objecte Request com argument que resperesnta la solicitud HTTP realitzada al servidor
        //validate fields
        $attrs = $request->validate([// es crida al metode 'validate' en l'obj. request. Aixo valida les dades de la solicitud d'acord a les regles proporc. en l'array
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:9|unique:users,phone',
            'password' => 'required|min:6|confirmed',
            'municipio_id' => 'required|integer',
            'role' => 'sometimes|string|in:votant,organitzacio'

        ]);
        // un cop validades les dades, es pasen al metode create de la clase User, que crea un nou registre a la taula users de la bd
        // create user
        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'phone' => $attrs['phone'],
            'password' => bcrypt($attrs['password']), // s'encripta la pass utilitzant bcrypt abans d'emmagatzemarla
            'municipio_id' => $attrs['municipio_id'],
            'role' => $attrs['role'] ?? 'votant'  // Establece 'votant' como valor predeterminado si 'role' no está presente.
        ]);

        // retornem una resposta (user & token) HTTP al client un cop l'usuari s'ha registrat
        return response([
                'user' => $user,
                'role' => $user->role,
                'token' => $user->createToken('secret')->plainTextToken
        ]);

    }
    //login user
    public function login(Request $request){
        //validate fields
        $attrs = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        // intentar logejar
        // el metode attempt de Laravel comprova si existeix un usr amb email/pass passats per attrs. Si les credencials son vàlides torna TRUE 
        if(!Auth::attempt($attrs))// la variable attrs es un array q conté les dades validades anteriorment
        {
            return response([
                'message' => 'Credenciales incorrectas'
            ], 403);
        }
        // retornem una resposta (user & token) HTTP al client un cop l'usuari s'ha registrat
        return response([
                'user' => auth()->user(), // afegim un objecte usuari a la respota *** auth()->user() es una forma d'accedir al usuari autenticat acutalment
                'role' => auth()->user()->role,
                'token' => auth()->user()->createToken('secret')->plainTextToken // es genera un nou token d'autentificacio per l'usuari i s'afageix a la resposta
                                                    // secret es el nom del token i plainTextToken es un metode que converteix el token a text pla pq pugui ser retornat al client   
        ], 200);

    }
    // logout user
    public function logout(){
        auth()->user()->tokens()->delete(); // eliminem tots els tokens d'auth asociats al usuari autentificat actualment
        return response([
            'message' => 'Logout success.'
        ], 200);
    }

    //get user details
    public function user()
    {
        return response([
            'user' => auth()->user()
        ], 200);
    }

}
