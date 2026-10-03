@extends('plantilla.layout')
@section('titulo', 'Categorías')
@section('contenido')
<section class="space-y-5"><div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-sm font-semibold uppercase text-cyan-700">Catálogo</p><h1 class="mt-1 text-2xl font-bold">Categorías</h1></div><a class="rounded-md bg-cyan-600 px-4 py-2 text-sm font-semibold text-white" href="{{ route('categorias.crear') }}">Nueva categoría</a></div>
@if (session('success'))<p class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('success') }}</p>@endif
@if (session('mensaje'))<div class="alert alert-success alert-dismissible fade show rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800" role="alert">{{ session('mensaje') }}</div>@endif
<div class="overflow-x-auto rounded-md border border-slate-200 bg-white"><table class="w-full text-left text-sm"><thead class="bg-slate-100 text-xs uppercase text-slate-600"><tr>@foreach (array_keys($categorias[0] ?? []) as $campo)<th class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $campo)) }}</th>@endforeach<th class="px-4 py-3">Acciones</th></tr></thead><tbody>
@forelse ($categorias as $registro)<tr class="border-t border-slate-200">@foreach ($registro as $valor)<td class="px-4 py-3">{{ $valor }}</td>@endforeach<td class="flex gap-3 px-4 py-3"><a class="text-cyan-700" href="{{ route('categorias.mostrar', $registro['id']) }}">Ver</a><a class="text-blue-700" href="{{ route('categorias.editar', $registro['id']) }}">Editar</a><form method="POST" action="{{ route('categorias.borrar', $registro['id']) }}">@csrf @method('DELETE')<button class="text-red-700" type="submit">Eliminar</button></form></td></tr>
@empty<tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay categorías registradas.</td></tr>@endforelse
</tbody></table></div></section>
@endsection