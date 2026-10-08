@extends('plantilla.layout')
@section('titulo', 'Editar producto')
@section('contenido')
<section class="mx-auto max-w-3xl space-y-5">
    <div>
        <p class="text-sm font-semibold uppercase text-cyan-700">Catálogo e inventario</p>
        <h1 class="mt-1 text-2xl font-bold">Editar producto #{{ $registro['id'] }}</h1>
    </div>

    @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('productos.actualizar', $registro['id'] ?? 1) }}" enctype="multipart/form-data" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6 sm:grid-cols-2">
        @csrf
        @method('PUT')

        @foreach ($registro as $campo => $valor)
            @if($campo !== 'id')
                @if($campo === 'imagen')
                    <div class="grid gap-2 text-sm font-medium text-slate-700 sm:col-span-2">
                        <span>Imagen del producto (Almacenamiento en API externa ImgBB)</span>

                        @if(!empty($valor))
                            <div class="flex items-center gap-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                @php
                                    $urlImg = \Illuminate\Support\Str::startsWith($valor, ['http://', 'https://']) ? $valor : asset('storage/' . $valor);
                                @endphp
                                <img src="{{ $urlImg }}" alt="Imagen actual" class="h-16 w-16 rounded-md object-cover border border-slate-300 shadow-sm" referrerpolicy="no-referrer">
                                <div class="text-xs text-slate-600 truncate">
                                    <p class="font-semibold text-slate-800">Imagen actual en la nube:</p>
                                    <a href="{{ $urlImg }}" target="_blank" class="text-cyan-600 underline truncate block max-w-xs sm:max-w-md">{{ $urlImg }}</a>
                                </div>
                            </div>
                        @endif

                        <label class="grid gap-1 mt-1">
                            <span class="text-xs text-slate-500">Seleccionar nueva imagen para actualizar en la API (opcional):</span>
                            <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp,image/gif" class="rounded-md border border-slate-300 px-3 py-2 text-sm bg-white">
                        </label>
                        @error('imagen') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </div>
                @else
                    @php($tipo = in_array($campo, ['precio', 'existencia', 'descuento']) ? 'number' : 'text')
                    <label class="grid gap-1 text-sm font-medium text-slate-700">
                        {{ ucfirst(str_replace('_', ' ', $campo)) }}
                        <input name="{{ $campo }}" type="{{ $tipo }}" value="{{ old($campo, $valor) }}" @if(in_array($campo, ['precio', 'descuento'])) step="0.01" min="0" @endif class="rounded-md border border-slate-300 px-3 py-2">
                        @error($campo) <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                    </label>
                @endif
            @endif
        @endforeach

        <div class="flex gap-3 sm:col-span-2 mt-2">
            <button class="rounded-md bg-cyan-600 px-5 py-2.5 font-semibold text-white hover:bg-cyan-700 transition" type="submit">
                Actualizar producto
            </button>
            <a class="rounded-md border border-slate-300 px-4 py-2.5 text-slate-700 hover:bg-slate-50 transition" href="{{ route('productos.listar') }}">
                Cancelar
            </a>
        </div>
    </form>
</section>
@endsection