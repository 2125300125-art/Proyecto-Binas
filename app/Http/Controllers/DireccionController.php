<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    private const MODELO = Direccion::class;

    private array $datos = [
        ['id' => 1, 'cliente' => 'Ana Sofía Ramírez', 'calle' => 'Avenida Reforma', 'numero' => '120', 'colonia' => 'Centro', 'ciudad' => 'Puebla', 'codigo_postal' => '72000'],
        ['id' => 2, 'cliente' => 'Luis Hernández', 'calle' => 'Calle Lago', 'numero' => '45', 'colonia' => 'Jardines', 'ciudad' => 'Puebla', 'codigo_postal' => '72100'],
    ];

    public function listar()
    {
        $direcciones = Direccion::query()->with('cliente')->get()->map(fn (Direccion $direccion) => [
            'id' => $direccion->id,
            'cliente' => $direccion->cliente->nombres . ' ' . $direccion->cliente->apellidos,
            'calle' => $direccion->calle,
            'numero' => $direccion->numero,
            'colonia' => $direccion->colonia,
            'ciudad' => $direccion->ciudad,
            'codigo_postal' => $direccion->codigo_postal,
            'imagen' => $direccion->imagen,
            'estado' => $direccion->estado ? 'Activa' : 'Inactiva',
        ])->all();

        return view('direcciones.listado', compact('direcciones'));
    }

    public function vistaFormulario()
    {
        return view('direcciones.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('direcciones', $this->datos, $request);

        return redirect()->route('direcciones.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('direcciones', $this->datos, (int) $id);

        return view('direcciones.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('direcciones', $this->datos, $request, (int) $id);

        return redirect()->route('direcciones.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('direcciones', $this->datos, (int) $id);

        return view('direcciones.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('direcciones', $this->datos, (int) $id);

        return redirect()->route('direcciones.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}