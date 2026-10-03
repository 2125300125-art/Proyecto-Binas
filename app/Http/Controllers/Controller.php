<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function datosTemporales(string $clave, array $datosIniciales): array
    {
        $claveSesion = 'datos_temporales_' . $clave;
        $claveVacia = 'datos_temporales_vacios_' . $clave;
        $datos = session()->get($claveSesion);

        if ($datos === null || ($datos === [] && !session()->get($claveVacia, false))) {
            $datos = $datosIniciales;
            session()->put($claveSesion, $datos);
        }

        return $datos;
    }

    protected function registrarDatoTemporal(string $clave, array $datosIniciales, Request $request): void
    {
        $datos = $this->datosTemporales($clave, $datosIniciales);
        $ids = array_map('intval', array_column($datos, 'id'));
        $registro = $request->except(['_token', '_method', 'id']);
        $registro = array_merge(['id' => ($ids === [] ? 0 : max($ids)) + 1], $registro);

        $datos[] = $registro;
        session()->put('datos_temporales_' . $clave, $datos);
        session()->forget('datos_temporales_vacios_' . $clave);
    }

    protected function actualizarDatoTemporal(string $clave, array $datosIniciales, Request $request, int $id): void
    {
        $datos = $this->datosTemporales($clave, $datosIniciales);
        $cambios = $request->except(['_token', '_method', 'id']);

        foreach ($datos as $indice => $registro) {
            if ((int) $registro['id'] === $id) {
                $datos[$indice] = array_merge($registro, $cambios);
                break;
            }
        }

        session()->put('datos_temporales_' . $clave, $datos);
        session()->forget('datos_temporales_vacios_' . $clave);
    }

    protected function borrarDatoTemporal(string $clave, array $datosIniciales, int $id): void
    {
        $datos = $this->datosTemporales($clave, $datosIniciales);
        $datos = array_values(array_filter($datos, fn ($registro) => (int) $registro['id'] !== $id));

        session()->put('datos_temporales_' . $clave, $datos);

        if ($datos === []) {
            session()->put('datos_temporales_vacios_' . $clave, true);
        } else {
            session()->forget('datos_temporales_vacios_' . $clave);
        }
    }

    protected function buscarDatoTemporal(string $clave, array $datosIniciales, int $id): array
    {
        $datos = $this->datosTemporales($clave, $datosIniciales);

        foreach ($datos as $registro) {
            if ((int) $registro['id'] === $id) {
                return $registro;
            }
        }

        return $datos[0] ?? [];
    }
}
