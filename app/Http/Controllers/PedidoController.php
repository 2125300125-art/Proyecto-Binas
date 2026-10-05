<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\MetodoPago;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    private const MODELO = Pedido::class;

    public function listar()
    {
        $pedidos = Pedido::query()->with(['cliente', 'metodoPago'])->get()->map(fn (Pedido $pedido) => [
            'id' => $pedido->id,
            'cliente' => $pedido->cliente ? ($pedido->cliente->nombres . ' ' . $pedido->cliente->apellidos) : 'Cliente',
            'metodo_pago' => $pedido->metodoPago ? $pedido->metodoPago->nombre : 'Efectivo',
            'fecha' => $pedido->fecha,
            'total' => '$' . number_format((float) $pedido->total, 2),
            'imagen' => $pedido->imagen,
            'estado' => $pedido->estado ? 'Activo' : 'Inactivo',
        ])->all();

        return view('pedidos.listado', compact('pedidos'));
    }

    public function vistaFormulario()
    {
        return view('pedidos.formulario');
    }

    public function registrar(Request $request)
    {
        if ($request->filled('total') || $request->filled('cliente')) {
            $clienteInput = $request->input('cliente');
            $clienteId = 1;
            if (is_numeric($clienteInput)) {
                $clienteId = (int) $clienteInput;
            } elseif (!empty($clienteInput)) {
                $c = Cliente::where('nombres', 'like', "%{$clienteInput}%")->orWhere('apellidos', 'like', "%{$clienteInput}%")->first();
                $clienteId = $c ? $c->id : (Cliente::first()->id ?? 1);
            } else {
                $clienteId = Cliente::first()->id ?? 1;
            }

            $metodoInput = $request->input('metodo_pago');
            $metodoId = 1;
            if (is_numeric($metodoInput)) {
                $metodoId = (int) $metodoInput;
            } elseif (!empty($metodoInput)) {
                $m = MetodoPago::where('nombre', 'like', "%{$metodoInput}%")->first();
                $metodoId = $m ? $m->id : (MetodoPago::first()->id ?? 1);
            } else {
                $metodoId = MetodoPago::first()->id ?? 1;
            }

            $total = (float) $request->input('total', 100.00);

            Pedido::create([
                'cliente_id' => $clienteId,
                'metodo_pago_id' => $metodoId,
                'fecha' => $request->input('fecha', date('Y-m-d')),
                'iva' => round($total * 0.16, 2),
                'descuento' => 0.00,
                'total' => $total,
                'estado' => true,
            ]);
        }

        return redirect()->route('pedidos.listar')->with('success', 'Pedido guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $pedido = Pedido::find($id) ?? Pedido::first();

        $registro = [
            'id' => $pedido ? $pedido->id : (int) $id,
            'fecha' => $pedido ? $pedido->fecha : date('Y-m-d'),
            'total' => $pedido ? $pedido->total : '0.00',
            'estado' => ($pedido && $pedido->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('pedidos.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $pedido = Pedido::find($id) ?? Pedido::first();

        if ($pedido) {
            if ($request->filled('fecha')) $pedido->fecha = $request->input('fecha');
            if ($request->filled('total')) {
                $pedido->total = (float) $request->input('total');
                $pedido->iva = round($pedido->total * 0.16, 2);
            }
            if ($request->has('estado')) {
                $pedido->estado = in_array(strtolower($request->input('estado')), ['1', 'true', 'activo', 'activa'], true);
            }
            $pedido->save();
        }

        return redirect()->route('pedidos.listar')->with('success', 'Pedido actualizado exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $pedido = Pedido::find($id) ?? Pedido::first();

        $registro = [
            'id' => $pedido ? $pedido->id : (int) $id,
            'cliente' => ($pedido && $pedido->cliente) ? ($pedido->cliente->nombres . ' ' . $pedido->cliente->apellidos) : 'Cliente',
            'metodo_pago' => ($pedido && $pedido->metodoPago) ? $pedido->metodoPago->nombre : 'Efectivo',
            'fecha' => $pedido ? $pedido->fecha : date('Y-m-d'),
            'total' => '$' . number_format((float) ($pedido ? $pedido->total : 0), 2),
            'iva' => '$' . number_format((float) ($pedido ? $pedido->iva : 0), 2),
            'descuento' => '$' . number_format((float) ($pedido ? $pedido->descuento : 0), 2),
            'imagen' => $pedido ? $pedido->imagen : null,
            'estado' => ($pedido && $pedido->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('pedidos.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $pedido = Pedido::find($id) ?? Pedido::first();

        if ($pedido) {
            try {
                $pedido->delete();
            } catch (\Throwable $e) {}
        }

        return redirect()->route('pedidos.listar')->with('success', 'Pedido eliminado exitosamente.');
    }
}