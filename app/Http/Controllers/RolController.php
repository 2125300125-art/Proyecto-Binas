<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\Http\Request;

class RolController extends Controller
{
    private const MODELO = Rol::class;

    public function listar()
    {
        $roles = Rol::query()->get()->map(fn (Rol $rol) => [
            'id' => $rol->id,
            'nombre' => $rol->nombre,
            'imagen' => $rol->imagen,
            'estado' => $rol->estado ? 'Activo' : 'Inactivo',
        ])->all();

        return view('roles.listado', compact('roles'));
    }

    public function vistaFormulario()
    {
        return view('roles.formulario');
    }

    public function registrar(Request $request)
    {
        if ($request->filled('nombre')) {
            Rol::firstOrCreate(
                ['nombre' => $request->input('nombre')],
                ['estado' => in_array(strtolower($request->input('estado', 'Activo')), ['1', 'true', 'activo', 'activa'], true)]
            );
        }

        return redirect()->route('roles.listar')->with('success', 'Rol guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $rol = Rol::find($id) ?? Rol::first();

        $registro = [
            'id' => $rol ? $rol->id : (int) $id,
            'nombre' => $rol ? $rol->nombre : '',
            'estado' => ($rol && $rol->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('roles.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $rol = Rol::find($id) ?? Rol::first();

        if ($rol) {
            if ($request->filled('nombre')) $rol->nombre = $request->input('nombre');
            if ($request->has('estado')) {
                $rol->estado = in_array(strtolower($request->input('estado')), ['1', 'true', 'activo', 'activa'], true);
            }
            $rol->save();
        }

        return redirect()->route('roles.listar')->with('success', 'Rol actualizado exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $rol = Rol::find($id) ?? Rol::first();

        $registro = [
            'id' => $rol ? $rol->id : (int) $id,
            'nombre' => $rol ? $rol->nombre : '',
            'imagen' => $rol ? $rol->imagen : null,
            'estado' => ($rol && $rol->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('roles.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $rol = Rol::find($id) ?? Rol::first();

        if ($rol) {
            try {
                $rol->delete();
            } catch (\Throwable $e) {}
        }

        return redirect()->route('roles.listar')->with('success', 'Rol eliminado exitosamente.');
    }
}