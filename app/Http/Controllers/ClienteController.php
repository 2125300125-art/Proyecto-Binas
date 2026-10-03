<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    private const MODELO = Cliente::class;

    private array $datos = [
        ['id' => 1, 'nombres' => 'Ana Sofía', 'apellidos' => 'Ramírez Cruz', 'correo' => 'ana.ramirez@correo.test', 'telefono' => '5552468101', 'estado' => 'Activo'],
        ['id' => 2, 'nombres' => 'Luis', 'apellidos' => 'Hernández Díaz', 'correo' => 'luis.hernandez@correo.test', 'telefono' => '5551357911', 'estado' => 'Activo'],
    ];

    public function listar()
    {
        $clientes = Cliente::query()->get(['id', 'nombres', 'apellidos', 'correo', 'telefono', 'estado'])->map(fn (Cliente $cliente) => [
            'id' => $cliente->id,
            'nombres' => $cliente->nombres,
            'apellidos' => $cliente->apellidos,
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'estado' => $cliente->estado ? 'Activo' : 'Inactivo',
        ])->all();

        return view('clientes.listado', compact('clientes'));
    }

    public function vistaFormulario()
    {
        return $this->create();
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function registrar(Request $request)
    {
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombres' => ['required', 'string', 'max:255'],
            'apellidos' => ['required', 'string', 'max:255'],
            'correo' => ['required', 'email', 'max:255', 'unique:clientes,correo'],
            'contraseña' => ['required', 'string', 'min:8', 'max:255'],
            'telefono' => ['required', 'string', 'digits:10'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $datos = $validator->validated();
        $datos['contraseña'] = Hash::make($datos['contraseña']);
        Cliente::create($datos);

        return redirect()->route('clientes.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('clientes', $this->datos, (int) $id);

        return view('clientes.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $this->actualizarDatoTemporal('clientes', $this->datos, $request, (int) $id);

        return redirect()->route('clientes.listar')->with('success', 'Registro actualizado solo para esta sesión.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('clientes', $this->datos, (int) $id);

        return view('clientes.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('clientes', $this->datos, (int) $id);

        return redirect()->route('clientes.listar')->with('success', 'Registro eliminado solo para esta sesión.');
    }
}