@extends('plantilla.layout')
@section('titulo', 'Nuevo producto')
@section('contenido')
<section class="mx-auto max-w-3xl space-y-5">
    <div><p class="text-sm font-semibold uppercase text-cyan-700">Catálogo e inventario</p><h1 class="mt-1 text-2xl font-bold">Nuevo producto</h1></div>
    @if ($marcas->isEmpty() || $presentaciones->isEmpty())
        <div class="rounded-md border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900" role="alert">
            <p class="font-semibold">Faltan datos del catálogo para guardar este producto.</p>
            <ul class="mt-2 list-inside list-disc space-y-1">
                @if ($marcas->isEmpty())
                    <li>No hay marcas registradas. <a class="font-semibold underline" href="{{ route('marcas.crear') }}">Registrar una marca</a>.</li>
                @endif
                @if ($presentaciones->isEmpty())
                    <li>No hay presentaciones registradas. <a class="font-semibold underline" href="{{ route('presentaciones.crear') }}">Registrar una presentación</a>.</li>
                @endif
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data" class="grid gap-4 rounded-md border border-slate-200 bg-white p-6 sm:grid-cols-2">
        @csrf
        <label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Nombre
            <input name="nombre" type="text" value="{{ old('nombre') }}" maxlength="255" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('nombre') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Descripción
            <textarea name="descripcion" rows="3" class="rounded-md border border-slate-300 px-3 py-2">{{ old('descripcion') }}</textarea>
            @error('descripcion') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Categoría
            <select name="categoria_id" required class="rounded-md border border-slate-300 px-3 py-2">
                <option value="">Selecciona una categoría</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id') == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
            @error('categoria_id') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Marca
            <select name="marca_id" required class="rounded-md border border-slate-300 px-3 py-2">
                <option value="">Selecciona una marca</option>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}" @selected(old('marca_id') == $marca->id)>{{ $marca->nombre }}</option>
                @endforeach
            </select>
            @error('marca_id') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Presentación
            <select name="presentacion_id" required class="rounded-md border border-slate-300 px-3 py-2">
                <option value="">Selecciona una presentación</option>
                @foreach ($presentaciones as $presentacion)
                    <option value="{{ $presentacion->id }}" @selected(old('presentacion_id') == $presentacion->id)>{{ $presentacion->nombre }}</option>
                @endforeach
            </select>
            @error('presentacion_id') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Precio
            <input name="precio" type="number" step="0.01" min="0" value="{{ old('precio') }}" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('precio') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Existencia
            <input name="existencia" type="number" min="0" step="1" value="{{ old('existencia', 0) }}" required class="rounded-md border border-slate-300 px-3 py-2">
            @error('existencia') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700">Descuento
            <input name="descuento" type="number" step="0.01" min="0" value="{{ old('descuento', 0) }}" class="rounded-md border border-slate-300 px-3 py-2">
            @error('descuento') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <label class="grid gap-1 text-sm font-medium text-slate-700 sm:col-span-2">Imagen (JPEG, PNG, JPG o WEBP; máximo 2 MB)
            <input type="file" name="imagen" accept="image/jpeg,image/png,image/webp" class="rounded-md border border-slate-300 px-3 py-2">
            @error('imagen') <span class="text-danger text-sm text-red-600">{{ $message }}</span> @enderror
        </label>
        <div class="flex gap-3 sm:col-span-2"><button class="rounded-md bg-cyan-600 px-4 py-2 font-semibold text-white disabled:cursor-not-allowed disabled:opacity-50" type="submit" @disabled($marcas->isEmpty() || $presentaciones->isEmpty())>Guardar producto</button><a class="rounded-md border border-slate-300 px-4 py-2" href="{{ route('productos.index') }}">Cancelar</a></div>
    </form>
</section>
@endsection
