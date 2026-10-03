<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    private const MODELO = Rol::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Administradora', 'descripcion' => 'Acceso a la gestión general', 'usuarios' => 1, 'estado' => 'Activo'],
        ['id' => 2, 'nombre' => 'Operador', 'descripcion' => 'Apoyo en pedidos y entregas', 'usuarios' => 2, 'estado' => 'Activo'],
    ];

    public function listar()
    {
        return view('roles.listado', ['roles' => $this->datosTemporales('roles', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('roles.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('roles', $this->datos, $request);

        return redirect()->route('roles.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('roles', $this->datos, (int) $id);

        return view('roles.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('roles', $this->datos, $request, (int) $id);

        return redirect()->route('roles.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('roles', $this->datos, (int) $id);

        return view('roles.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('roles', $this->datos, (int) $id);

        return redirect()->route('roles.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}