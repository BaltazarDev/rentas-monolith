@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Inquilinos</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Directorio de inquilinos y sus contratos.</p>
        </div>
        
        @can('tenants.create')
            <a href="{{ route('tenants.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Nuevo Inquilino
            </a>
        @endcan
    </div>

    <!-- Search Bar -->
    <div class="relative max-w-md">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </span>
        <input type="text" id="tenant-search" placeholder="Buscar inquilino por nombre, contacto o propiedad..." class="w-full rounded-2xl border border-slate-205 dark:border-slate-700 pl-11 pr-4 py-3 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm focus:outline-none transition">
    </div>

    <!-- Tenants List -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700 text-slate-400 text-xs font-semibold uppercase tracking-wider bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-6 py-4">Inquilino</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4">Unidad Asignada</th>
                        <th class="px-6 py-4">Día de Corte</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-sm">
                    @forelse($tenants as $tenant)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-750/50 transition duration-150 tenant-row">
                            <td class="px-6 py-4">
                                <a href="{{ route('tenants.show', $tenant->id) }}" class="font-bold text-slate-850 dark:text-slate-100 hover:text-indigo-600 dark:hover:text-indigo-400 flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs">
                                        {{ substr($tenant->full_name, 0, 1) }}
                                    </span>
                                    <span>{{ $tenant->full_name }}</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-500 dark:text-slate-400 text-xs">
                                <div class="space-y-0.5">
                                    @if($tenant->phone)
                                        <p class="flex items-center gap-1.5 font-medium text-slate-700 dark:text-slate-300">
                                            <span>📱</span> {{ $tenant->phone }}
                                        </p>
                                    @endif
                                    @if($tenant->email)
                                        <p class="text-slate-400 truncate max-w-[180px]">
                                            {{ $tenant->email }}
                                        </p>
                                    @endif
                                    @if(!$tenant->phone && !$tenant->email)
                                        <span class="text-slate-350 dark:text-slate-500 italic">Sin datos de contacto</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($tenant->unit)
                                    <a href="{{ route('units.show', $tenant->unit->id) }}" class="inline-flex flex-col">
                                        <span class="font-bold text-slate-800 dark:text-slate-200 hover:text-indigo-600">
                                            {{ $tenant->unit->name }}
                                        </span>
                                        <span class="text-xs text-slate-400">
                                            {{ $tenant->unit->house->name ?? 'Casa' }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Sin asignar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold {{ $tenant->is_active ? 'text-amber-600 bg-amber-50 dark:text-amber-450 dark:bg-amber-950/20' : 'text-slate-400 bg-slate-100 dark:text-slate-500 dark:bg-slate-750' }}">
                                    {{ $tenant->is_active ? 'Día ' . $tenant->payment_due_day : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2.5">
                                    @can('payments.create')
                                        @if($tenant->unit_id)
                                            <button 
                                                onclick="Livewire.dispatch('openTransactionModal', {type: 'payment', unitId: '{{ $tenant->unit_id }}'})" 
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-450 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 transition shadow-sm"
                                                title="Registrar cobro de renta para este inquilino"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                Cobrar
                                            </button>
                                        @endif
                                    @endcan

                                    @can('tenants.edit')
                                        <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Editar
                                        </a>
                                    @endcan

                                    @can('tenants.delete')
                                        <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este inquilino? Se liberará la unidad asignada.')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-rose-600 hover:underline bg-transparent border-0 p-0 cursor-pointer">
                                                Eliminar
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                No hay inquilinos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('tenant-search');
        const rows = document.querySelectorAll('tbody tr');

        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            rows.forEach(row => {
                // Skip empty row if exists
                if (row.cells.length === 1 && row.cells[0].colSpan === 5) return;
                
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
