@extends('plantilla.layout')
@section('titulo', 'Nuevo método de pago')
@section('contenido')
<section class="mx-auto max-w-2xl space-y-5"><div><p class="text-sm font-semibold uppercase text-cyan-700">Cobros</p><h1 class="mt-1 text-2xl font-bold">Nuevo método de pago</h1></div><form method="POST" action="{{ route('metodos_pago.guardar') }}" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6">@csrf
@foreach (['nombre', 'descripcion', 'disponible'] as $campo)<label class="grid gap-1 text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $campo)) }}<input name="{{ $campo }}" type="text" value="{{ old($campo) }}" class="rounded-md border border-slate-300 px-3 py-2" required></label>@endforeach
<div class="flex gap-3"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white" type="submit">Guardar</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('metodos_pago.listar') }}">Cancelar</a></div></form></section>
@endsection