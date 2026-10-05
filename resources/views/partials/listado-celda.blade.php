@if ($campo === 'imagen')
    @if ($valor)
        <img src="{{ asset('storage/' . $valor) }}" alt="Imagen del registro #{{ $registro['id'] }}" class="h-14 w-14 rounded object-cover" loading="lazy">
    @else
        <span class="text-slate-500">Sin imagen</span>
    @endif
@else
    {{ $valor ?? '—' }}
@endif
