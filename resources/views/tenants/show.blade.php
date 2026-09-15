@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('tenants.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-550 truncate">{{ $tenant->full_name }}</h1>
                <p class="text-xs text-slate-500 dark:text-slate-450 mt-0.5">Perfil de inquilino e historial de transacciones.</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @can('tenants.edit')
                <a href="{{ route('tenants.edit', $tenant->id) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition" title="Editar Inquilino">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </a>
            @endcan
            
            @can('tenants.delete')
                <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este inquilino? Se liberará la unidad asignada.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition" title="Eliminar Inquilino">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- Info Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Contact Details Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4 md:col-span-2">
            <h3 class="text-sm font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Detalles de Contacto</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nombre Completo</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $tenant->full_name }}</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Estado del Contrato</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $tenant->is_active ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/30' : 'text-slate-500 bg-slate-100 dark:text-slate-400 dark:bg-slate-750' }}">
                        {{ $tenant->is_active ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Número Celular</span>
                    @if($tenant->phone)
                        <div class="flex items-center gap-2">
                            <a href="tel:{{ $tenant->phone }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                📞 {{ $tenant->phone }}
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tenant->phone) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-950/40 transition" title="Enviar WhatsApp">
                                💬 WhatsApp
                            </a>
                        </div>
                    @else
                        <span class="text-sm text-slate-400">Sin registrar</span>
                    @endif
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Correo Electrónico</span>
                    @if($tenant->email)
                        <a href="mailto:{{ $tenant->email }}" class="text-sm font-semibold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            ✉️ {{ $tenant->email }}
                        </a>
                    @else
                        <span class="text-sm text-slate-400">Sin registrar</span>
                    @endif
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fecha de Inicio del Contrato</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">📅 {{ \Carbon\Carbon::parse($tenant->start_date)->format('d M, Y') }}</span>
                </div>
                <div class="space-y-1">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Fecha de Término</span>
                    <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                        📅 {{ $tenant->end_date ? \Carbon\Carbon::parse($tenant->end_date)->format('d M, Y') : 'Indefinido' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Property Assignment Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <h3 class="text-sm font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Ubicación Asignada</h3>
            
            @if($tenant->unit)
                <div class="space-y-3.5">
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Propiedad</span>
                        <a href="{{ route('houses.show', $tenant->unit->house->id) }}" class="text-sm font-bold text-indigo-650 dark:text-indigo-400 hover:underline block mt-0.5">
                            🏠 {{ $tenant->unit->house->name ?? '' }}
                        </a>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unidad / Departamento</span>
                        <a href="{{ route('units.show', $tenant->unit->id) }}" class="text-sm font-semibold text-slate-805 dark:text-slate-200 hover:underline block mt-0.5">
                            🚪 {{ $tenant->unit->name }} ({{ $tenant->unit->type === 'commercial' ? 'Local' : 'Habitación' }})
                        </a>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Renta Base Mensual</span>
                        <span class="text-lg font-extrabold text-indigo-600 dark:text-indigo-400 block mt-0.5">
                            ${{ number_format($tenant->unit->base_rent_cost, 2) }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Día de Pago</span>
                        <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/20 px-2 py-0.5 rounded-md inline-block mt-1">
                            Día {{ $tenant->payment_due_day }} de cada mes
                        </span>
                    </div>
                </div>
            @else
                <div class="text-center py-6 text-slate-455 dark:text-slate-500">
                    <span class="text-2xl block mb-1">🚪</span>
                    <p class="text-xs">Este inquilino no tiene una unidad activa asignada en este momento.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes & Schedules Section -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-2">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                <span>📝</span> Notas, Horarios y Observaciones
            </h3>
            @can('tenants.edit')
                <a href="{{ route('tenants.edit', $tenant->id) }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Editar Notas
                </a>
            @endcan
        </div>
        @if($tenant->notes)
            <p class="text-sm text-slate-700 dark:text-slate-300 whitespace-pre-line leading-relaxed bg-slate-50/50 dark:bg-slate-900/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-800">
                {{ $tenant->notes }}
            </p>
        @else
            <p class="text-xs text-slate-400 italic py-2">Sin notas u horarios registrados para este inquilino.</p>
        @endif
    </div>

    <!-- Payments History Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
            <h3 class="text-base font-bold text-slate-805 dark:text-slate-200">Historial de Pagos</h3>
            @if($tenant->unit_id)
                <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment', unitId: '{{ $tenant->unit_id }}'})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-650 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/50 transition focus:outline-none">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Registrar Pago
                </button>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/20 dark:bg-slate-800/30 text-slate-450 dark:text-slate-500 text-[10px] font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-4">Concepto</th>
                        <th class="px-6 py-4">Fecha de Pago</th>
                        <th class="px-6 py-4">Comprobante</th>
                        <th class="px-6 py-4">Notas</th>
                        <th class="px-6 py-4 text-right">Monto</th>
                        @if(Auth::user()->isSuperAdmin())
                            <th class="px-6 py-4 text-right">Acción</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80">
                    @forelse($payments as $payment)
                        <tr class="text-slate-800 dark:text-slate-200 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $payment->type === 'rent' ? 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-950/20' : 'text-emerald-650 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/20' }}">
                                    {{ $payment->type === 'rent' ? 'Renta Mensual' : 'Servicios / Utilidades' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                📅 {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($payment->receipt_url)
                                    <a href="{{ $payment->receipt_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A3 3 0 1010.607 15m3.757-5.829l-3.757 3.757m0 0A3 3 0 105.636 18.364M21 21L15 15" /></svg>
                                        Ver Comprobante
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Sin archivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-400 italic max-w-xs truncate" title="{{ $payment->notes }}">
                                {{ $payment->notes ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-sm text-emerald-600 dark:text-emerald-450">
                                +${{ number_format($payment->amount, 2) }}
                            </td>
                            @if(Auth::user()->isSuperAdmin())
                                <td class="px-6 py-4 text-right">
                                    <button onclick="Livewire.dispatch('openEditPaymentModal', { paymentId: {{ $payment->id }} })" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition" title="Editar Pago">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Editar
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isSuperAdmin() ? 6 : 5 }}" class="px-6 py-12 text-center text-slate-400 text-sm">
                                No se han registrado pagos para este inquilino todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
