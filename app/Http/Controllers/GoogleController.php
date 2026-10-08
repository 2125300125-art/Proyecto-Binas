<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirige al usuario a la página de autenticación de Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Recibe la información del usuario desde Google y gestiona el inicio de sesión.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Obtener el usuario autenticado por Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'No se pudo autenticar con Google: ' . $e->getMessage()
            ]);
        }

        // 1. Sincronizar también con la tabla de usuarios generales (modelo User)
        try {
            User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name'     => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)),
                ]
            );
        } catch (\Exception $e) {
            // Si la tabla users no está lista, continuamos con administradores
        }

        // 2. Buscar si ya existe un Administrador con este correo electrónico
        $admin = Administrador::where('correo', $googleUser->getEmail())->first();

        if ($admin) {
            // Verificar si el administrador se encuentra activo
            if (isset($admin->activo) && (int)$admin->activo === 0) {
                return redirect()->route('login')->withErrors([
                    'error' => 'Esta cuenta de administrador se encuentra desactivada.'
                ]);
            }

            // Actualizar imagen con la foto de perfil de Google
            if ($googleUser->getAvatar()) {
                $admin->imagen = $googleUser->getAvatar();
                $admin->save();
            }

            // Iniciar sesión con el guard 'admin' de tu proyecto
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            return redirect()->intended(route('inicio'));
        }

        // 3. Si no existe como administrador, lo creamos automáticamente como nuevo Administrador activo
        try {
            $nombrePartes = explode(' ', $googleUser->getName(), 2);
            $nombre = $nombrePartes[0] ?? $googleUser->getName();
            $apellidos = $nombrePartes[1] ?? 'Google';
            $usuario = explode('@', $googleUser->getEmail())[0];

            // Asegurarse de que el usuario no esté duplicado
            if (Administrador::where('usuario', $usuario)->exists()) {
                $usuario .= rand(10, 99);
            }

            $nuevoAdmin = Administrador::create([
                'nombre'     => $nombre,
                'apellidos'  => $apellidos,
                'correo'     => $googleUser->getEmail(),
                'usuario'    => $usuario,
                'contraseña' => Hash::make(Str::random(24)),
                'imagen'     => $googleUser->getAvatar(),
                'rol_id'     => 1,
                'activo'     => 1,
            ]);

            Auth::guard('admin')->login($nuevoAdmin);
            $request->session()->regenerate();

            return redirect()->intended(route('inicio'));

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors([
                'error' => 'Error al registrar administrador desde Google: ' . $e->getMessage()
            ]);
        }
    }
}

