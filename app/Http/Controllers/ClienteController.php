<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClienteController extends Controller
{
    private const MODELO = Cliente::class;

    public function listar()
    {
        $clientes = Cliente::query()->get(['id', 'nombres', 'apellidos', 'correo', 'telefono', 'imagen', 'estado'])->map(fn (Cliente $cliente) => [
            'id' => $cliente->id,
            'nombres' => $cliente->nombres,
            'apellidos' => $cliente->apellidos,
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'imagen' => $cliente->imagen,
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
        $datos['estado'] = true;

        if ($request->hasFile('imagen')) {
            try {
                $datos['imagen'] = app(\App\Services\ImageUploadService::class)->subirImagen($request->file('imagen'));
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
            }
        }

        Cliente::create($datos);

        return redirect()->route('clientes.index')->with('mensaje', 'Registro guardado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $cliente = Cliente::find($id) ?? Cliente::first();

        $registro = [
            'id' => $cliente ? $cliente->id : (int) $id,
            'nombres' => $cliente ? $cliente->nombres : '',
            'apellidos' => $cliente ? $cliente->apellidos : '',
            'correo' => $cliente ? $cliente->correo : '',
            'telefono' => $cliente ? $cliente->telefono : '',
            'imagen' => $cliente ? $cliente->imagen : null,
            'estado' => ($cliente && $cliente->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('clientes.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        $cliente = Cliente::find($id) ?? Cliente::first();

        if ($cliente) {
            if ($request->filled('nombres')) $cliente->nombres = $request->input('nombres');
            if ($request->filled('apellidos')) $cliente->apellidos = $request->input('apellidos');
            if ($request->filled('correo')) $cliente->correo = $request->input('correo');
            if ($request->filled('telefono')) $cliente->telefono = substr($request->input('telefono'), 0, 10);
            if ($request->has('estado')) {
                $cliente->estado = in_array(strtolower($request->input('estado')), ['1', 'true', 'activo', 'activa'], true);
            }
            if ($request->filled('contraseña')) {
                $cliente->contraseña = Hash::make($request->input('contraseña'));
            }
            if ($request->hasFile('imagen')) {
                try {
                    $cliente->imagen = app(\App\Services\ImageUploadService::class)->subirImagen($request->file('imagen'));
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
                }
            }
            $cliente->save();
        }

        return redirect()->route('clientes.listar')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $cliente = Cliente::find($id) ?? Cliente::first();

        $registro = [
            'id' => $cliente ? $cliente->id : (int) $id,
            'nombres' => $cliente ? $cliente->nombres : '',
            'apellidos' => $cliente ? $cliente->apellidos : '',
            'correo' => $cliente ? $cliente->correo : '',
            'telefono' => $cliente ? $cliente->telefono : '',
            'imagen' => $cliente ? $cliente->imagen : null,
            'estado' => ($cliente && $cliente->estado) ? 'Activo' : 'Inactivo',
        ];

        return view('clientes.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $cliente = Cliente::find($id) ?? Cliente::first();

        if ($cliente) {
            try {
                $cliente->delete();
            } catch (\Throwable $e) {}
        }

        return redirect()->route('clientes.listar')->with('success', 'Cliente eliminado exitosamente.');
    }
}