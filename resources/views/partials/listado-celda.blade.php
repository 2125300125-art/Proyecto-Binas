@if ($campo === 'imagen')
    @if ($valor)
        @php
            $urlImagen = \Illuminate\Support\Str::startsWith($valor, ['http://', 'https://']) ? $valor : asset('storage/' . $valor);
        @endphp
        <img src="{{ $urlImagen }}" alt="Imagen del registro #{{ $registro['id'] ?? '' }}" class="h-12 w-12 rounded-full object-cover border border-slate-300 shadow-sm" loading="lazy" referrerpolicy="no-referrer">
    @else
        <span class="text-slate-500">Sin imagen</span>
    @endif
@else
    {{ $valor ?? '—' }}
@endif
