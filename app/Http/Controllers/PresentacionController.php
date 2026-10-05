<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresentacionController extends Controller
{
    private const MODELO = Presentacion::class;

    public function listar()
    {
        $presentaciones = Presentacion::query()->get(['id', 'nombre', 'descripcion', 'imagen', 'estado'])->map(fn (Presentacion $presentacion) => [
            'id' => $presentacion->id,
            'nombre' => $presentacion->nombre,
            'descripcion' => $presentacion->descripcion,
            'imagen' => $presentacion->imagen,
            'estado' => $presentacion->estado ? 'Activa' : 'Inactiva',
        ])->all();

        return view('presentaciones.listado', compact('presentaciones'));
    }

    public function vistaFormulario()
    {
        return $this->create();
    }

    public function create()
    {
        return view('presentaciones.create');
    }

    public function registrar(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255', 'unique:presentaciones,nombre'],
            'descripcion' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datos = $validator->validated();
        $datos['estado'] = true;
        Presentacion::create($datos);

        return redirect()->route('presentaciones.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $presentacion = Presentacion::find($id) ?? Presentacion::first();

        $registro = [
            'id' => $presentacion ? $presentacion->id : (int) $id,
            'nombre' => $presentacion ? $presentacion->nombre : '',
            'descripcion' => $presentacion ? $presentacion->descripcion : '',
            'estado' => ($presentacion && $presentacion->estado) ? 'Activa' : 'Inactiva',
        ];

        return view('presentaciones.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $presentacion = Presentacion::find($id) ?? Presentacion::first();

        if ($presentacion) {
            if ($request->filled('nombre')) $presentacion->nombre = $request->input('nombre');
            if ($request->has('descripcion')) $presentacion->descripcion = $request->input('descripcion');
            if ($request->has('estado')) {
                $presentacion->estado = in_array(strtolower($request->input('estado')), ['1', 'true', 'activa', 'activo'], true);
            }
            $presentacion->save();
        }

        return redirect()->route('presentaciones.listar')->with('success', 'Presentación actualizada exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $presentacion = Presentacion::find($id) ?? Presentacion::first();

        $registro = [
            'id' => $presentacion ? $presentacion->id : (int) $id,
            'nombre' => $presentacion ? $presentacion->nombre : '',
            'descripcion' => $presentacion ? $presentacion->descripcion : '',
            'imagen' => $presentacion ? $presentacion->imagen : null,
            'estado' => ($presentacion && $presentacion->estado) ? 'Activa' : 'Inactiva',
        ];

        return view('presentaciones.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $presentacion = Presentacion::find($id) ?? Presentacion::first();

        if ($presentacion) {
            try {
                $presentacion->delete();
            } catch (\Throwable $e) {}
        }

        return redirect()->route('presentaciones.listar')->with('success', 'Presentación eliminada exitosamente.');
    }
}