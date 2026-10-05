<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use Illuminate\Http\Request;

class EntregaController extends Controller
{
    private const MODELO = Entrega::class;

    private array $datos = [
        ['id' => 1, 'pedido_id' => 1, 'cliente' => 'Ana Sofía Ramírez', 'repartidor' => 'Carlos Mendoza', 'direccion' => 'Av. Reforma 120, Centro', 'fecha' => '2026-09-25', 'hora' => '10:30', 'estado' => 'Programada'],
        ['id' => 2, 'pedido_id' => 2, 'cliente' => 'Luis Hernández', 'repartidor' => 'Patricia Flores', 'direccion' => 'Calle Lago 45, Jardines', 'fecha' => '2026-09-25', 'hora' => '12:00', 'estado' => 'En ruta'],
    ];

    public function listar()
    {
        $entregas = Entrega::query()->with(['pedido.cliente', 'repartidor', 'direccion.cliente'])->get()->map(fn (Entrega $entrega) => [
            'id' => $entrega->id,
            'pedido' => '#' . $entrega->pedido_id . ' — ' . $entrega->pedido->cliente->nombres . ' ' . $entrega->pedido->cliente->apellidos,
            'repartidor' => $entrega->repartidor->nombres . ' ' . $entrega->repartidor->apellidos,
            'direccion' => $entrega->direccion->calle . ' ' . $entrega->direccion->numero . ', ' . $entrega->direccion->colonia . ', ' . $entrega->direccion->ciudad,
            'fecha' => $entrega->fecha,
            'hora' => $entrega->hora,
            'imagen' => $entrega->imagen,
            'estado' => $entrega->estado ? 'Activa' : 'Inactiva',
        ])->all();

        return view('entregas.listado', compact('entregas'));
    }

    public function vistaFormulario()
    {
        return view('entregas.formulario');
    }

    public function registrar(Request $request)
    {
        $this->registrarDatoTemporal('entregas', $this->datos, $request);

        return redirect()->route('entregas.listar')->with('success', 'Registro agregado solo para esta sesión.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('entregas', $this->datos, (int) $id);

        return view('entregas.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('entregas', $this->datos, $request, (int) $id);

        return redirect()->route('entregas.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('entregas', $this->datos, (int) $id);

        return view('entregas.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('entregas', $this->datos, (int) $id);

        return redirect()->route('entregas.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}