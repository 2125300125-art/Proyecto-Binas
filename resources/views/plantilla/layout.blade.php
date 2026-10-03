<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Área administrativa') | Distribuidora de Agua Potable</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-white">
    <header class="border-b border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
        <nav class="mx-auto flex max-w-7xl flex-wrap items-center justify-between p-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-cyan-500 text-xl font-bold text-slate-950">A</span>
                <span class="text-xl font-semibold">Distribuidora de Agua Potable</span>
            </a>
            <button data-collapse-toggle="admin-navbar" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 md:hidden" aria-controls="admin-navbar" aria-expanded="false">
                <span class="sr-only">Abrir menú</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div id="admin-navbar" class="hidden w-full md:block md:w-auto">
                <ul class="mt-4 grid grid-cols-2 gap-2 font-medium md:mt-0 md:flex md:flex-wrap md:items-center md:gap-4">
                    <li><a href="{{ url('/') }}" class="hover:text-cyan-700">Inicio</a></li>
                    <li><a href="{{ route('inicio') }}" class="hover:text-cyan-700">Panel</a></li>
                    @foreach (['administradores' => 'Administradores', 'productos' => 'Productos', 'productos_pedido' => 'Productos por pedido', 'clientes' => 'Clientes', 'pedidos' => 'Pedidos', 'repartidores' => 'Repartidores', 'entregas' => 'Entregas', 'categorias' => 'Categorías', 'marcas' => 'Marcas', 'presentaciones' => 'Presentaciones', 'direcciones' => 'Direcciones', 'metodos_pago' => 'Métodos de pago', 'roles' => 'Roles', 'users' => 'Usuarios'] as $modulo => $etiqueta)
                        <li><a href="{{ route($modulo . '.listar') }}" class="hover:text-cyan-700">{{ $etiqueta }}</a></li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl p-4 pt-8 sm:p-6 lg:p-8">
        @yield('contenido')
    </main>

    <footer class="mt-8 border-t border-slate-200 bg-white p-6 text-center text-sm text-slate-500 dark:border-slate-800 dark:bg-slate-900">
        Área administrativa · Distribuidora de Agua Potable
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>
