@extends('plantilla.layout')
@section('titulo', 'Agregar producto a pedido')
@section('contenido')
<section class="mx-auto max-w-2xl space-y-5"><div><p class="text-sm font-semibold uppercase text-cyan-700">Pedidos</p><h1 class="mt-1 text-2xl font-bold">Agregar producto a pedido</h1></div><form method="POST" action="{{ route('productos_pedido.guardar') }}" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6">@csrf
    @foreach (['pedido_id', 'producto_id', 'cantidad', 'precio', 'descuento', 'estado'] as $campo)<label class="grid gap-1 text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $campo)) }}<input name="{{ $campo }}" type="{{ in_array($campo, ['pedido_id', 'producto_id', 'cantidad']) ? 'number' : 'text' }}" value="{{ old($campo) }}" class="rounded-md border border-slate-300 px-3 py-2" required></label>@endforeach
    <div class="flex gap-3"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white" type="submit">Registrar</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('productos_pedido.listar') }}">Cancelar</a></div>
</form></section>
@endsection