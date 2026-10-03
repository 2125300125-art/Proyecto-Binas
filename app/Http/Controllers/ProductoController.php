<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacion;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    private const MODELO = Producto::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Garrafón de agua 20 L', 'marca' => 'Ciel', 'presentacion' => 'Garrafón retornable', 'precio' => 48.00, 'existencia' => 36, 'estado' => 'Activo'],
        ['id' => 2, 'nombre' => 'Agua purificada 1.5 L', 'marca' => 'Bonafont', 'presentacion' => 'Botella individual', 'precio' => 16.50, 'existencia' => 84, 'estado' => 'Activo'],
    ];

    public function listar()
    {
        $productos = Producto::query()->with(['categoria', 'marca', 'presentacion'])->get()->map(fn (Producto $producto) => [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'marca' => $producto->marca->nombre,
            'presentacion' => $producto->presentacion->nombre,
            'precio' => $producto->precio,
            'existencia' => $producto->existencia,
            'estado' => $producto->estado ? 'Activo' : 'Inactivo',
        ])->all();

        return view('productos.listado', compact('productos'));
    }

    public function vistaFormulario()
    {
        return $this->create();
    }

    public function create()
    {
        $categorias = Categoria::all();
        $marcas = Marca::all();
        $presentaciones = Presentacion::all();

        return view('productos.create', compact('categorias', 'marcas', 'presentaciones'));
    }

    public function registrar(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'presentacion_id' => ['required', 'exists:presentaciones,id'],
            'marca_id' => ['required', 'exists:marcas,id'],
            'precio' => ['required', 'numeric', 'min:0'],
            'existencia' => ['required', 'integer', 'min:0'],
            'descuento' => ['nullable', 'numeric', 'min:0'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datos = $validator->validated();

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('productos', 'public');

            if ($ruta === false) {
                throw new \RuntimeException('No se pudo guardar la imagen del producto.');
            }

            $datos['imagen'] = $ruta;
        }

        Producto::create($datos);

        return redirect()->route('productos.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('productos', $this->datos, (int) $id);

        return view('productos.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('productos', $this->datos, $request, (int) $id);

        return redirect()->route('productos.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('productos', $this->datos, (int) $id);

        return view('productos.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('productos', $this->datos, (int) $id);

        return redirect()->route('productos.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}