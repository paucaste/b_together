<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Municipio;

class MunicipioController extends Controller
{
    public function showAllMunicipios()
    {
        $municipios = Municipio::all();
        return response()->json($municipios);
    }

    public function search(Request $request)
        {
            $query = $request->get('query');
            $municipios = Municipio::where('nombre', 'LIKE', "%{$query}%")->get();
            return response()->json($municipios);
        }

}
