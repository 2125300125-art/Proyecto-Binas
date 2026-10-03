@extends('plantilla.layout')

@section('titulo', 'Nuevo cliente')

@section('contenido')
<section class="mx-auto max-w-3xl space-y-5"><div><p class="text-sm font-semibold uppercase text-cyan-700">Cartera</p><h1 class="mt-1 text-2xl font-bold">Nuevo cliente</h1></div>
    <form method="POST" action="{{ route('clientes.guardar') }}" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6 sm:grid-cols-2">
        @csrf
        @foreach (['nombres', 'apellidos', 'correo', 'telefono', 'estado'] as $campo)
            <label class="grid gap-1 text-sm font-medium text-slate-700">{{ ucfirst(str_replace('_', ' ', $campo)) }}<input name="{{ $campo }}" type="{{ $campo === 'correo' ? 'email' : 'text' }}" value="{{ old($campo) }}" class="rounded-md border border-slate-300 px-3 py-2" required></label>
        @endforeach
        <div class="flex gap-3 sm:col-span-2"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white" type="submit">Guardar cliente</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('clientes.listar') }}">Cancelar</a></div>
    </form>
</section>
@endsection