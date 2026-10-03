<?php

namespace App\Http\Controllers;

use App\Models\MetodoPago;
use Illuminate\Http\Request;

class MetodoPagoController extends Controller
{
    private const MODELO = MetodoPago::class;

    private array $datos = [
        ['id' => 1, 'nombre' => 'Efectivo', 'descripcion' => 'Pago al recibir el pedido', 'disponible' => 'En tienda y entrega'],
        ['id' => 2, 'nombre' => 'Transferencia', 'descripcion' => 'Transferencia bancaria', 'disponible' => 'Pago anticipado'],
    ];

    public function listar()
    {
        return view('metodos_pago.listado', ['metodos_pago' => $this->datosTemporales('metodos_pago', $this->datos)]);
    }

    public function vistaFormulario()
    {
        return view('metodos_pago.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('metodos_pago', $this->datos, $request);

        return redirect()->route('metodos_pago.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('metodos_pago', $this->datos, (int) $id);

        return view('metodos_pago.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('metodos_pago', $this->datos, $request, (int) $id);

        return redirect()->route('metodos_pago.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('metodos_pago', $this->datos, (int) $id);

        return view('metodos_pago.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('metodos_pago', $this->datos, (int) $id);

        return redirect()->route('metodos_pago.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}