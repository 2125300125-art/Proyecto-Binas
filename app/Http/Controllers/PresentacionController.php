<?php

namespace App\Http\Controllers;

use App\Models\Presentacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PresentacionController extends Controller
{
    private const MODELO = Presentacion::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Garrafón retornable 20 L', 'capacidad' => '20 litros', 'envase' => 'Retornable', 'estado' => 'Activa'],
        ['id' => 2, 'nombre' => 'Botella individual 1.5 L', 'capacidad' => '1.5 litros', 'envase' => 'Desechable', 'estado' => 'Activa'],
    ];

    public function listar()
    {
        $presentaciones = Presentacion::query()->get(['id', 'nombre', 'descripcion', 'estado'])->map(fn (Presentacion $presentacion) => [
            'id' => $presentacion->id,
            'nombre' => $presentacion->nombre,
            'descripcion' => $presentacion->descripcion,
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

        Presentacion::create($validator->validated());

        return redirect()->route('presentaciones.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('presentaciones', $this->datos, (int) $id);

        return view('presentaciones.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('presentaciones', $this->datos, $request, (int) $id);

        return redirect()->route('presentaciones.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('presentaciones', $this->datos, (int) $id);

        return view('presentaciones.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('presentaciones', $this->datos, (int) $id);

        return redirect()->route('presentaciones.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}