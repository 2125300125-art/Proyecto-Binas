<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLoginForm()
    {
        // Si el admin ya inició sesión, redirigir al panel
        if (Auth::guard('admin')->check()) {
            return redirect()->route('inicio');
        }

        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión manual con el guard 'admin'.
     */
    public function login(Request $request)
    {
        // 1. Validar que los campos no vengan vacíos

        $request->validate([
            'usuario' => 'required|string',
            'contraseña' => 'required|string',
        ], [
            'usuario.required' => 'El campo usuario es obligatorio.',
            'contraseña.required' => 'El campo contraseña es obligatorio.',
        ]);

        // 2. Verificar si el usuario existe y si está desactivado
        $admin = Administrador::where('usuario', $request->usuario)->first();

        // Nota: Si mantuviste la columna como 'estado', cambia ->activo por ->estado
        if ($admin && !$admin->activo) {
            return back()->withErrors([
                'error' => 'Esta cuenta se encuentra desactivada.'
            ])->withInput($request->only('usuario'));
        }

        // 3. Credenciales para el intento de acceso
        // 'password' es obligatorio como llave para Auth::attempt, Laravel lo mapea con getAuthPassword()
        $credenciales = [
            'usuario'  => $request->usuario,
            'password' => $request->contraseña,
            'activo'   => 1, // Si dejaste 'estado', pon 'estado' => 1
        ];

        // 4. Intentar autenticar con el guard personalizado 'admin'
        if (Auth::guard('admin')->attempt($credenciales)) {
            $request->session()->regenerate();
            return redirect()->intended(route('inicio'));
        }

        // 5. Si fallan las credenciales
        return back()->withErrors([
            'error' => 'Credenciales incorrectas o cuenta inactiva.'
        ])->withInput($request->only('usuario'));
    }

    /**
     * Cierra la sesión del administrador.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
