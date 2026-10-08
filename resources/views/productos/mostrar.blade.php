@extends('plantilla.layout')
@section('titulo', 'Detalle de producto')
@section('contenido')
<section class="mx-auto max-w-3xl space-y-5">
    <div>
        <p class="text-sm font-semibold uppercase text-cyan-700">Catálogo e inventario</p>
        <h1 class="mt-1 text-2xl font-bold">Producto #{{ $registro['id'] }}</h1>
    </div>

    <dl class="grid gap-px overflow-hidden rounded-md border border-slate-200 bg-slate-200 sm:grid-cols-2">
        @foreach ($registro as $campo => $valor)
            <div class="bg-white p-4">
                <dt class="text-xs font-semibold uppercase text-slate-500">{{ ucfirst(str_replace('_', ' ', $campo)) }}</dt>
                <dd class="mt-1">
                    @if ($campo === 'imagen')
                        @if (!empty($valor))
                            @php
                                $url = \Illuminate\Support\Str::startsWith($valor, ['http://', 'https://']) ? $valor : asset('storage/' . $valor);
                            @endphp
                            <div class="mt-1 space-y-2">
                                <img src="{{ $url }}" alt="Imagen del producto" class="h-32 w-32 rounded-lg object-cover border border-slate-300 shadow-sm" referrerpolicy="no-referrer">
                                <a href="{{ $url }}" target="_blank" class="text-xs text-cyan-600 underline block truncate max-w-xs">{{ $url }}</a>
                            </div>
                        @else
                            <span class="text-slate-400">Sin imagen</span>
                        @endif
                    @else
                        {{ $valor ?? '—' }}
                    @endif
                </dd>
            </div>
        @endforeach
    </dl>

    <div class="flex gap-3">
        <a class="inline-flex rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white hover:bg-cyan-700 transition" href="{{ route('productos.editar', $registro['id']) }}">
            Editar producto
        </a>
        <a class="inline-flex rounded-md border border-slate-300 px-4 py-2 text-slate-700 hover:bg-slate-50 transition" href="{{ route('productos.listar') }}">
            Volver al listado
        </a>
    </div>
</section>
@endsection