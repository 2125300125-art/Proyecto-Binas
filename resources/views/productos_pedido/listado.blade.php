@extends('plantilla.layout')
@section('titulo', 'Productos por pedido')
@section('contenido')
<section class="space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold uppercase text-cyan-700">Pedidos</p><h1 class="mt-1 text-2xl font-bold">Productos por pedido</h1></div><a class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-semibold text-white" href="{{ route('productos_pedido.crear') }}">Agregar producto a pedido</a></div>
    @if (session('success'))<p class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</p>@endif
    <div class="overflow-x-auto rounded-md border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">Pedido</th><th class="px-4 py-3">Producto</th><th class="px-4 py-3">Cantidad</th><th class="px-4 py-3">Precio</th><th class="px-4 py-3">Descuento</th><th class="px-4 py-3">Estado</th><th class="px-4 py-3">Acciones</th></tr></thead><tbody>
    @forelse ($productos_pedido as $registro)
        <tr class="border-t border-slate-200"><td class="px-4 py-3">{{ $registro['id'] }}</td><td class="px-4 py-3">{{ $registro['pedido_id'] }}</td><td class="px-4 py-3">{{ $registro['producto_id'] }}</td><td class="px-4 py-3">{{ $registro['cantidad'] }}</td><td class="px-4 py-3">{{ $registro['precio'] }}</td><td class="px-4 py-3">{{ $registro['descuento'] }}</td><td class="px-4 py-3">{{ $registro['estado'] }}</td><td class="flex gap-3 px-4 py-3"><a class="text-cyan-700" href="{{ route('productos_pedido.mostrar', $registro['id']) }}">Ver</a><a class="text-blue-700" href="{{ route('productos_pedido.editar', $registro['id']) }}">Editar</a><form method="POST" action="{{ route('productos_pedido.borrar', $registro['id']) }}">@csrf @method('DELETE')<button class="text-red-700" type="submit">Eliminar</button></form></td></tr>
    @empty<tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">No hay productos de pedido de prueba.</td></tr>@endforelse
    </tbody></table></div>
</section>
@endsection