<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use Illuminate\Http\Request;

class RepartidorController extends Controller
{
    private const MODELO = Repartidor::class;

    public function listar()
    {
        $repartidores = Repartidor::query()->get()->map(fn (Repartidor $repartidor) => [
            'id' => $repartidor->id,
            'nombres' => $repartidor->nombres,
            'apellidos' => $repartidor->apellidos,
            'telefono' => $repartidor->telefono,
            'licencia' => $repartidor->licencia,
            'imagen' => $repartidor->imagen,
            'estado' => $repartidor->estado ? 'Activo' : 'Inactivo',
        ])->all();

        return view('repartidores.listado', compact('repartidores'));
    }

    public function vistaFormulario()
    {
        return view('repartidores.formulario');
    }

    public function registrar(Request $request)
    {
        if ($request->filled('nombres')) {
            Repartidor::create([
                'nombres' => $request->input('nombres'),
                'apellidos' => $request->input('apellidos', ''),
                'telefono' => substr($request->input('telefono', '0000000000'), 0, 10),
                'licencia' => $request->input('licencia', 'LIC-' . rand(1000, 9999)),
                'estado' => true,
            ]);
        }

        return redirect()->route('repartidores.listar')->with('success', 'Repartidor guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $rep = Repartidor::find($id) ?? Repartidor::first();

        $registro = [
            'id' => $rep ? $rep->id : (int) $id,
            'nombres' => $rep ? $rep->nombres : '',
            'apellidos' => $rep ? $rep->apellidos : '',
            'telefono' => $rep ? $rep->telefono : '',
            'licencia' => $rep ? $rep->licencia : '',
            'estado' => ($rep && $rep->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('repartidores.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $rep = Repartidor::find($id) ?? Repartidor::first();

        if ($rep) {
            if ($request->filled('nombres')) $rep->nombres = $request->input('nombres');
            if ($request->filled('apellidos')) $rep->apellidos = $request->input('apellidos');
            if ($request->filled('telefono')) $rep->telefono = substr($request->input('telefono'), 0, 10);
            if ($request->filled('licencia')) $rep->licencia = $request->input('licencia');
            if ($request->has('estado')) {
                $rep->estado = in_array(strtolower($request->input('estado')), ['1', 'true', 'activo', 'activa'], true);
            }
            $rep->save();
        }

        return redirect()->route('repartidores.listar')->with('success', 'Repartidor actualizado exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $rep = Repartidor::find($id) ?? Repartidor::first();

        $registro = [
            'id' => $rep ? $rep->id : (int) $id,
            'nombres' => $rep ? $rep->nombres : '',
            'apellidos' => $rep ? $rep->apellidos : '',
            'telefono' => $rep ? $rep->telefono : '',
            'licencia' => $rep ? $rep->licencia : '',
            'imagen' => $rep ? $rep->imagen : null,
            'estado' => ($rep && $rep->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('repartidores.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $rep = Repartidor::find($id) ?? Repartidor::first();

        if ($rep) {
            try {
                $rep->delete();
            } catch (\Throwable $e) {}
        }

        return redirect()->route('repartidores.listar')->with('success', 'Repartidor eliminado exitosamente.');
    }
}