<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacion;
use App\Models\Producto;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    private const MODELO = Producto::class;

    private ImageUploadService $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    private array $datos = [
        ['id' => 1, 'nombre' => 'Garrafón de agua 20 L', 'marca' => 'Ciel', 'presentacion' => 'Garrafón retornable', 'precio' => 48.00, 'existencia' => 36, 'estado' => 'Activo'],
        ['id' => 2, 'nombre' => 'Agua purificada 1.5 L', 'marca' => 'Bonafont', 'presentacion' => 'Botella individual', 'precio' => 16.50, 'existencia' => 84, 'estado' => 'Activo'],
    ];

    public function listar()
    {
        $productos = Producto::query()->with(['categoria', 'marca', 'presentacion'])->get()->map(fn (Producto $producto) => [
            'id' => $producto->id,
            'nombre' => $producto->nombre,
            'categoria' => $producto->categoria->nombre ?? 'N/A',
            'marca' => $producto->marca->nombre ?? 'N/A',
            'presentacion' => $producto->presentacion->nombre ?? 'N/A',
            'precio' => $producto->precio,
            'existencia' => $producto->existencia,
            'imagen' => $producto->imagen,
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
            'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datos = $validator->validated();
        $imagen = $request->file('imagen');
        unset($datos['imagen']);

        // 1. Enviar la imagen a la API externa de almacenamiento
        if ($imagen !== null) {
            try {
                $datos['imagen'] = $this->imageService->subirImagen($imagen);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
            }
        }

        // 2. Guardar el registro en la base de datos con la URL pública devuelta por la API
        $producto = Producto::create($datos);

        return redirect()->route('productos.index')->with('mensaje', 'Producto registrado exitosamente con imagen almacenada en la API externa.');
    }

    public function vistaEdicion($id = 1)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $registro = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'existencia' => $producto->existencia,
                'descuento' => $producto->descuento,
                'imagen' => $producto->imagen,
            ];
        } else {
            $registro = $this->buscarDatoTemporal('productos', $this->datos, (int) $id);
        }

        return view('productos.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $validator = Validator::make($request->all(), [
                'nombre' => ['sometimes', 'required', 'string', 'max:255'],
                'descripcion' => ['nullable', 'string'],
                'precio' => ['sometimes', 'required', 'numeric', 'min:0'],
                'existencia' => ['sometimes', 'required', 'integer', 'min:0'],
                'descuento' => ['nullable', 'numeric', 'min:0'],
                'imagen' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $datos = $validator->validated();

            // Si el usuario seleccionó una nueva imagen, subirla a la API externa
            if ($request->hasFile('imagen')) {
                try {
                    $datos['imagen'] = $this->imageService->subirImagen($request->file('imagen'));
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
                }
            } else {
                unset($datos['imagen']);
            }

            $producto->update($datos);
        }

        $this->actualizarDatoTemporal('productos', $this->datos, $request, (int) $id);

        return redirect()->route('productos.listar')->with('success', 'Producto actualizado exitosamente (con imagen en API externa).');
    }

    public function vistaMostrar($id = 1)
    {
        $producto = Producto::find($id);

        if ($producto) {
            $registro = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'categoria' => $producto->categoria->nombre ?? 'N/A',
                'marca' => $producto->marca->nombre ?? 'N/A',
                'presentacion' => $producto->presentacion->nombre ?? 'N/A',
                'precio' => $producto->precio,
                'existencia' => $producto->existencia,
                'descuento' => $producto->descuento,
                'imagen' => $producto->imagen,
                'estado' => $producto->estado ? 'Activo' : 'Inactivo',
            ];
        } else {
            $registro = $this->buscarDatoTemporal('productos', $this->datos, (int) $id);
        }

        return view('productos.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $producto = Producto::find($id);
        if ($producto) {
            $producto->delete();
        }

        $this->borrarDatoTemporal('productos', $this->datos, (int) $id);

        return redirect()->route('productos.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}