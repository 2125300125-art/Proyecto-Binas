<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    private const MODELO = Marca::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Ciel', 'origen' => 'México', 'productos' => 4, 'estado' => 'Activa'],
        ['id' => 2, 'nombre' => 'Bonafont', 'origen' => 'México', 'productos' => 5, 'estado' => 'Activa'],
    ];

    public function listar()
    {
        $marcas = Marca::query()->get(['id', 'nombre', 'estado'])->map(fn (Marca $marca) => [
            'id' => $marca->id,
            'nombre' => $marca->nombre,
            'estado' => $marca->estado ? 'Activa' : 'Inactiva',
        ])->all();

        return view('marcas.listado', compact('marcas'));
    }

    public function vistaFormulario()
    {
        return $this->create();
    }

    public function create()
    {
        return view('marcas.create');
    }

    public function registrar(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255', 'unique:marcas,nombre'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Marca::create($validator->validated());

        return redirect()->route('marcas.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('marcas', $this->datos, (int) $id);

        return view('marcas.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('marcas', $this->datos, $request, (int) $id);

        return redirect()->route('marcas.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('marcas', $this->datos, (int) $id);

        return view('marcas.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('marcas', $this->datos, (int) $id);

        return redirect()->route('marcas.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}