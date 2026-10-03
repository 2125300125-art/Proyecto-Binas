<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private const MODELO = User::class;

    private array $datos = [
        ['id' => 1, 'name' => 'Usuario de prueba', 'email' => 'usuario@ejemplo.test'],
    ];

    public function listar()
    {
        return view('users.listado', ['users' => $this->datosTemporales('users', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('users.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('users', $this->datos, $request);

        return redirect()->route('users.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('users', $this->datos, (int) $id);

        return view('users.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('users', $this->datos, $request, (int) $id);

        return redirect()->route('users.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('users', $this->datos, (int) $id);

        return view('users.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('users', $this->datos, (int) $id);

        return redirect()->route('users.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}