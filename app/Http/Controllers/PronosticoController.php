<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PronosticoController extends Controller
{
    public function obtenerPronostico(Request $request)
    {
    
        $ip = $request->ip();
        $ubicacion = Http::get("http://ip-api.com/json/1.178.192.128");

        if ($ubicacion->failed()) {
            return response()->json(['error' => 'No se pudo obtener la ubicación para el clima'], 500);
        }

        $lat = $ubicacion['lat'];
        $lon = $ubicacion['lon'];
        $ciudad = $ubicacion['city'];

       //open mateo
        $clima = Http::get("https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lon}&current=temperature_2m,relative_humidity_2m,precipitation&daily=precipitation_probability_max&timezone=America%2FMexico_City");

        if ($clima->failed()) {
            return response()->json(['error' => 'No se pudo obtener el pronóstico del clima'], 500);
        }

        // 3. Enviamos los datos decodificados a la vista
        $datosClima = $clima->json();

        return view('geolocalizacion.pronostico', compact('datosClima', 'ciudad'));
    }
}
