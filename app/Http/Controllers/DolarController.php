<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class DolarController extends Controller
{
     public function obtenerDolar(Request $request)
    {
        // 1. Peticion a la API de DolarDOF
        $respuesta = Http::get('https://dolardof.com/api/v1/dof/today');

        if ($respuesta->failed()) {
            return response()->json(['error' => 'No se pudo obtener la tasa de cambio'], 500);
        }

        // 2. Convertimos el JSON a arreglo
        $datosDolar = $respuesta->json();

        // 3. Enviamos los datos a la vista correspondiente
        return view('geolocalizacion.cambio', compact('datosDolar'));
}
}

