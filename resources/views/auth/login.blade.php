<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesion | Distribuidora de Agua Potable</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-100 p-4 dark:bg-slate-950">
    <div
        class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-lg dark:border-slate-800 dark:bg-slate-900">

        {{-- Encabezado con el logotipo --}}
        <div class="text-center">
            <div
                class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-500 text-2xl font-bold text-slate-950">
                A
            </div>
            <h1 class="mt-4 text-2xl font-extrabold text-slate-900 dark:text-white">Acceso Administrativo</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Distribuidora de Agua Potable</p>
        </div>

        {{-- Contenedor de alertas para errores--}}
        @if ($errors->any())
            <div
                class="mt-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-900/30 dark:text-red-400">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulario de login --}}
        <form action="{{ route('login.post') }}" method="POST" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="usuario"
                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Usuario</label>
                <input type="text" name="usuario" id="usuario" value="{{ old('usuario') }}" required autofocus
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    placeholder="Ingresa tu usuario">
            </div>

            <div>
                <label for="contraseña"
                    class="block text-sm font-semibold text-slate-700 dark:text-slate-300">Contraseña</label>
                <input type="password" name="contraseña" id="contraseña" required
                    class="mt-1 block w-full rounded-lg border border-slate-300 bg-slate-50 p-2.5 text-sm text-slate-900 focus:border-cyan-500 focus:ring-cyan-500 dark:border-slate-700 dark:bg-slate-800 dark:text-white"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-cyan-600 px-5 py-3 text-center text-sm font-bold text-white transition hover:bg-cyan-700 focus:ring-4 focus:ring-cyan-300 dark:bg-cyan-500 dark:hover:bg-cyan-600">
                Entrar al Sistema
            </button>
        </form>

        {{-- Separador --}}
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-300 dark:border-slate-700"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-white px-3 text-slate-500 dark:bg-slate-900 dark:text-slate-400">O también</span>
            </div>
        </div>

        {{-- Botón de Google OAuth API --}}
        <a href="{{ route('google.login') }}"
            class="flex w-full items-center justify-center gap-3 rounded-lg border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:ring-4 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24">
                <path fill="#4285F4"
                    d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.65v3h3.88c2.27-2.09 3.66-5.17 3.66-9.09z" />
                <path fill="#34A853"
                    d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.27v3.09C3.27 21.41 7.34 24 12 24z" />
                <path fill="#FBBC05"
                    d="M5.28 14.32c-.25-.72-.38-1.49-.38-2.32s.13-1.6.38-2.32V6.59H1.27C.46 8.21 0 10.05 0 12s.46 3.79 1.27 5.41l4.01-3.09z" />
                <path fill="#EA4335"
                    d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.34 0 3.27 2.59 1.27 6.59l4.01 3.09c.95-2.83 3.6-4.93 6.72-4.93z" />
            </svg>
            <span>Continuar con Google</span>
        </a>


    </div>
</body>

</html>
