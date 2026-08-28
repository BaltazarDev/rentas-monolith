@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Inquilinos</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Directorio de inquilinos y sus contratos.</p>
        </div>
        
        <a href="{{ route('tenants.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Nuevo Inquilino
        </a>
    </div>

    <!-- Tenants List -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-slate-450 dark:text-slate-500 text-[10px] font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-4">Inquilino</th>
                        <th class="px-6 py-4">Contacto</th>
                        <th class="px-6 py-4">Asignación</th>
                        <th class="px-6 py-4">Pago Vence</th>
                        <th class="px-6 py-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80">
                    @forelse($tenants as $tenant)
                        <tr class="text-slate-800 dark:text-slate-200 hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-705 text-slate-500 dark:text-slate-400 flex items-center justify-center font-bold text-xs">
                                        {{ substr($tenant->full_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="text-sm font-bold block">{{ $tenant->full_name }}</span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-550 font-medium">Contrato desde: {{ \Carbon\Carbon::parse($tenant->start_date)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 space-y-1">
                                @if($tenant->phone)
                                    <a href="tel:{{ $tenant->phone }}" class="text-xs text-slate-500 dark:text-slate-400 hover:text-indigo-600 block">📞 {{ $tenant->phone }}</a>
                                @endif
                                @if($tenant->email)
                                    <a href="mailto:{{ $tenant->email }}" class="text-xs text-slate-550 dark:text-slate-400 hover:text-indigo-600 block">✉️ {{ $tenant->email }}</a>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tenant->unit)
                                    <a href="{{ route('units.show', $tenant->unit->id) }}" class="text-xs font-bold text-indigo-650 dark:text-indigo-400 hover:underline block">
                                        {{ $tenant->unit->house->name ?? '' }} - {{ $tenant->unit->name }}
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
                                <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                    Editar
                                </a>
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
@endsection
