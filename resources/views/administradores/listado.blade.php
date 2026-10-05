@extends('plantilla.layout')

@section('titulo', 'Administradores')

@section('contenido')
<section class="space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div><p class="text-sm font-semibold uppercase text-cyan-700">Equipo</p><h1 class="mt-1 text-2xl font-bold">Administradores</h1></div>
        <a href="{{ route('administradores.crear') }}" class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-semibold text-white hover:bg-cyan-700">Nuevo administrador</a>
    </div>
    @if (session('success'))<p class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</p>@endif
    <div class="overflow-x-auto rounded-md border border-slate-200 bg-white">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">Nombre</th><th class="px-4 py-3">Apellidos</th><th class="px-4 py-3">Correo</th><th class="px-4 py-3">Usuario</th><th class="px-4 py-3">Rol</th><th class="px-4 py-3">Imagen</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3">Acciones</th></tr></thead>
            <tbody>
                @forelse ($administradores as $admin)
                    <tr class="border-t border-slate-200">@foreach ($admin as $campo => $valor)<td class="px-4 py-3">@include('partials.listado-celda', ['campo' => $campo, 'valor' => $valor, 'registro' => $admin])</td>@endforeach
                        <td class="flex flex-wrap gap-2 px-4 py-3">
                            <a class="text-cyan-700 hover:underline" href="{{ route('administradores.mostrar', $admin['id']) }}">Ver</a>
                            <a class="text-blue-700 hover:underline" href="{{ route('administradores.editar', $admin['id']) }}">Editar</a>
                            <form method="POST" action="{{ route('administradores.borrar', $admin['id']) }}">@csrf @method('DELETE')<button class="text-red-700 hover:underline" type="submit">Eliminar</button></form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-4 py-8 text-center text-slate-500">No hay administradores registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
