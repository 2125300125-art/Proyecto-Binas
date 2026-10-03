<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    private const MODELO = Pedido::class;

    private array $datos = [
        ['id' => 1, 'cliente' => 'Ana Sofía Ramírez', 'productos' => '2 garrafones de 20 L', 'metodo_pago' => 'Efectivo', 'fecha' => '2026-09-24', 'total' => 96.00, 'estado' => 'En preparación'],
        ['id' => 2, 'cliente' => 'Luis Hernández', 'productos' => '4 botellas de 1.5 L', 'metodo_pago' => 'Transferencia', 'fecha' => '2026-09-25', 'total' => 66.00, 'estado' => 'Entregado'],
    ];

    public function listar()
    {
        return view('pedidos.listado', ['pedidos' => $this->datosTemporales('pedidos', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('pedidos.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('pedidos', $this->datos, $request);

        return redirect()->route('pedidos.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('pedidos', $this->datos, (int) $id);

        return view('pedidos.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('pedidos', $this->datos, $request, (int) $id);

        return redirect()->route('pedidos.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('pedidos', $this->datos, (int) $id);

        return view('pedidos.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('pedidos', $this->datos, (int) $id);

        return redirect()->route('pedidos.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}