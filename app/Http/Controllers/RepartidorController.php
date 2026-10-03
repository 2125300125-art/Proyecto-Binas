<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use Illuminate\Http\Request;

class RepartidorController extends Controller
{
    private const MODELO = Repartidor::class;

    private array $datos = [
        ['id' => 1, 'nombres' => 'Carlos', 'apellidos' => 'Mendoza Ruiz', 'telefono' => '5551122334', 'licencia' => 'LIC-AG-2041', 'vehiculo' => 'Camioneta reparto', 'estado' => 'Disponible'],
        ['id' => 2, 'nombres' => 'Patricia', 'apellidos' => 'Flores Vega', 'telefono' => '5554433221', 'licencia' => 'LIC-AG-1850', 'vehiculo' => 'Motocarga', 'estado' => 'En ruta'],
    ];

    public function listar()
    {
        return view('repartidores.listado', ['repartidores' => $this->datosTemporales('repartidores', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('repartidores.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('repartidores', $this->datos, $request);

        return redirect()->route('repartidores.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('repartidores', $this->datos, (int) $id);

        return view('repartidores.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('repartidores', $this->datos, $request, (int) $id);

        return redirect()->route('repartidores.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('repartidores', $this->datos, (int) $id);

        return view('repartidores.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('repartidores', $this->datos, (int) $id);

        return redirect()->route('repartidores.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}