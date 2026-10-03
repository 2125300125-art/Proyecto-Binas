<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriaController extends Controller
{
    private const MODELO = Categoria::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Agua purificada', 'descripcion' => 'Agua para consumo humano', 'productos' => 8, 'estado' => 'Activa'],
        ['id' => 2, 'nombre' => 'Accesorios', 'descripcion' => 'Bombas y soportes para garrafón', 'productos' => 3, 'estado' => 'Activa'],
    ];

    public function listar()
    {
        $categorias = Categoria::query()->get(['id', 'nombre', 'estado'])->map(fn (Categoria $categoria) => [
            'id' => $categoria->id,
            'nombre' => $categoria->nombre,
            'estado' => $categoria->estado ? 'Activa' : 'Inactiva',
        ])->all();

        return view('categorias.listado', compact('categorias'));
    }

    public function vistaFormulario()
    {
        return $this->create();
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function registrar(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Categoria::create($validator->validated());

        return redirect()->route('categorias.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('categorias', $this->datos, (int) $id);

        return view('categorias.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('categorias', $this->datos, $request, (int) $id);

        return redirect()->route('categorias.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('categorias', $this->datos, (int) $id);

        return view('categorias.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('categorias', $this->datos, (int) $id);

        return redirect()->route('categorias.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}