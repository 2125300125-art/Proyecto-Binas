@extends('plantilla.layout')
@section('titulo', 'Nuevo cliente')
@section('contenido')
<section class="mx-auto max-w-3xl space-y-5">
    <div><p class="text-sm font-semibold uppercase text-cyan-700">Cartera</p><h1 class="mt-1 text-2xl font-bold">Nuevo cliente</h1></div>
    <form method="POST" action="{{ route('clientes.store') }}" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6 sm:grid-cols-2">
        @csrf
        <label class="grid gap-1 text-sm font-medium text-slate-700">Nombres
            <input name="nombres" type="text" value="{{ old('nombres') }}" maxlength="255" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('nombres') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Apellidos
            <input name="apellidos" type="text" value="{{ old('apellidos') }}" maxlength="255" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('apellidos') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Correo electrónico
            <input name="correo" type="email" value="{{ old('correo') }}" maxlength="255" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('correo') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Teléfono (10 dígitos)
            <input name="telefono" type="tel" value="{{ old('telefono') }}" maxlength="10" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('telefono') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Contraseña (mínimo 8 caracteres)
            <input name="contraseña" type="password" minlength="8" required autocomplete="new-password" class="rounded-md border border-slate-300 px-3 py-2">
            @error('contraseña') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <div class="flex gap-3 sm:col-span-2"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white" type="submit">Guardar cliente</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('clientes.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
