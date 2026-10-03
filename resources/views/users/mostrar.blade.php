@extends('plantilla.layout')
@section('titulo', 'Detalle de usuario')
@section('contenido')
<section class="mx-auto max-w-2xl space-y-5"><div><p class="text-sm font-semibold uppercase text-cyan-700">Usuarios</p><h1 class="mt-1 text-2xl font-bold">Usuario #{{ $registro['id'] }}</h1></div><dl class="grid gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200 sm:grid-cols-2">@foreach ($registro as $campo => $valor)<div class="bg-white p-4"><dt class="text-xs font-semibold uppercase text-slate-500">{{ ucfirst($campo) }}</dt><dd class="mt-1">{{ $valor }}</dd></div>@endforeach</dl><a class="inline-flex rounded-md border border-slate-300 px-4 py-2" href="{{ route('users.listar') }}">Volver al listado</a></section>
@endsection