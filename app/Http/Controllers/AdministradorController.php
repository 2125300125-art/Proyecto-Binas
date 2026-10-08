<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    private const MODELO = Administrador::class;

    private array $datos = [
        [
            'id' => 1,
            'nombre' => 'Mariana',
            'apellidos' => 'López Rivera',
            'correo' => 'mariana.lopez@aguaclara.test',
            'usuario' => 'mlopez',
            'rol' => 'Administradora',
            'telefono' => '5551234567',
            'estado' => 'Activo',
        ],
        [
            'id' => 2,
            'nombre' => 'Jorge',
            'apellidos' => 'Sánchez Mora',
            'correo' => 'jorge.sanchez@aguaclara.test',
            'usuario' => 'jsanchez',
            'rol' => 'Operador',
            'telefono' => '5559876543',
            'estado' => 'Activo',
        ],
    ];

    public function listar()
    {
        $administradores = Administrador::query()->with('rol')->get()->map(fn (Administrador $administrador) => [
            'id' => $administrador->id,
            'nombre' => $administrador->nombre,
            'apellidos' => $administrador->apellidos,
            'correo' => $administrador->correo,
            'usuario' => $administrador->usuario,
            'rol' => $administrador->rol->nombre,
            'imagen' => $administrador->imagen,
            'estado' => ($administrador->activo ?? $administrador->estado) ? 'Activo' : 'Inactivo',
        ])->all();

        return view('administradores.listado', compact('administradores'));
    }

    public function vistaFormulario()
    {
        return view('administradores.formulario');
    }

    public function registrar(Request $request)
    {
        if ($request->hasFile('imagen')) {
            try {
                $url = app(\App\Services\ImageUploadService::class)->subirImagen($request->file('imagen'));
                $request->merge(['imagen' => $url]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
            }
        }

        $this->registrarDatoTemporal('administradores', $this->datos, $request);

        return redirect()->route('administradores.listar')->with('success', 'Administrador agregado exitosamente.');
    }

    public function vistaEdicion($id = 1)
    {
        $registro = $this->buscarDatoTemporal('administradores', $this->datos, (int) $id);

        return view('administradores.edicion', ['registro' => $registro]);
    }

    public function actualizar(Request $request, $id = 1)
    {
        if ($request->hasFile('imagen')) {
            try {
                $url = app(\App\Services\ImageUploadService::class)->subirImagen($request->file('imagen'));
                $request->merge(['imagen' => $url]);
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['imagen' => $e->getMessage()])->withInput();
            }
        }

        $this->actualizarDatoTemporal('administradores', $this->datos, $request, (int) $id);

        return redirect()->route('administradores.listar')->with('success', 'Administrador actualizado exitosamente.');
    }

    public function vistaMostrar($id = 1)
    {
        $registro = $this->buscarDatoTemporal('administradores', $this->datos, (int) $id);

        return view('administradores.mostrar', ['registro' => $registro]);
    }

    public function borrar(Request $request, $id = 1)
    {
        $this->borrarDatoTemporal('administradores', $this->datos, (int) $id);

        return redirect()->route('administradores.listar')->with('success', 'Administrador eliminado solo para esta sesión.');
    }
}
