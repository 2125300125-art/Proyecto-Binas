<?php

namespace App\Http\Controllers;

use App\Models\ProductoPedido;
use Illuminate\Http\Request;

class ProductoPedidoController extends Controller
{
    private const MODELO = ProductoPedido::class;

    private array $datos = [
        ['id' => 1, 'pedido_id' => 1, 'producto_id' => 1, 'cantidad' => 2, 'precio' => 48.00, 'descuento' => 0.00, 'estado' => 'Activo'],
        ['id' => 2, 'pedido_id' => 2, 'producto_id' => 2, 'cantidad' => 3, 'precio' => 16.50, 'descuento' => 2.00, 'estado' => 'Activo'],
    ];

    public function listar()
    {
        return view('productos_pedido.listado', ['productos_pedido' => $this->datosTemporales('productos_pedido', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('productos_pedido.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('productos_pedido', $this->datos, $request);

        return redirect()->route('productos_pedido.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('productos_pedido', $this->datos, (int) $id);

        return view('productos_pedido.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('productos_pedido', $this->datos, $request, (int) $id);

        return redirect()->route('productos_pedido.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('productos_pedido', $this->datos, (int) $id);

        return view('productos_pedido.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('productos_pedido', $this->datos, (int) $id);

        return redirect()->route('productos_pedido.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}