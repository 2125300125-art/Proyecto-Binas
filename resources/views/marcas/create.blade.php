@extends('plantilla.layout')
@section('titulo', 'Nueva marca')
@section('contenido')
<section class="mx-auto max-w-2xl space-y-5">
    <div><p class="text-sm font-semibold uppercase text-cyan-700">Catálogo</p><h1 class="mt-1 text-2xl font-bold">Nueva marca</h1></div>
    <form method="POST" action="{{ route('marcas.store') }}" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6">
        @csrf
        <label class="grid gap-1 text-sm font-medium text-slate-700">Nombre
            <input name="nombre" type="text" value="{{ old('nombre') }}" maxlength="255" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('nombre') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <div class="flex gap-3"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white" type="submit">Guardar</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('marcas.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
