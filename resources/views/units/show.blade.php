@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('houses.show', $unit->house_id) }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-50 truncate">{{ $unit->name }}</h1>
                <p class="text-xs text-slate-450 font-medium mt-0.5">{{ $unit->house->name }}</p>
            </div>
        </div>
        
        <div class="flex items-center gap-2">
            @can('units.edit')
                <a href="{{ route('units.edit', $unit) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition" title="Editar Unidad">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </a>
            @endcan

            @can('units.delete')
                <form action="{{ route('units.destroy', $unit) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar la unidad \'{{ $unit->name }}\'? Esta acción no se puede deshacer y borrará los pagos y registros asociados.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition" title="Eliminar Unidad">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            @endcan
        </div>
    </div>

    <!-- Summary Details Card -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="space-y-1">
            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Renta Mensual Base</p>
            <h3 class="text-3xl font-extrabold text-slate-850 dark:text-slate-100">${{ number_format($unit->base_rent_cost, 2) }}</h3>
        </div>
        
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $unit->status === 'occupied' ? 'text-emerald-600 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/20' : 'text-slate-500 bg-slate-100 dark:text-slate-400 dark:bg-slate-750' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $unit->status === 'occupied' ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                {{ $unit->status === 'occupied' ? 'OCUPADO' : 'DISPONIBLE' }}
            </span>
        </div>
    </div>

    <!-- Tenant Section -->
    @if($unit->status === 'occupied' && $unit->tenant)
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <div class="flex justify-between items-center">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Detalles del Inquilino</h3>
                @can('tenants.edit')
                    <a href="{{ route('tenants.edit', $unit->tenant->id) }}" class="text-xs font-semibold text-indigo-650 dark:text-indigo-400 hover:underline">Editar</a>
                @endcan
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Nombre Completo</span>
                    <span class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $unit->tenant->full_name }}</span>
                </div>
                
                @if($unit->tenant->phone)
                    <div>
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Número Celular</span>
                        <div class="flex items-center gap-2 mt-0.5">
                            <a href="tel:{{ $unit->tenant->phone }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                📞 {{ $unit->tenant->phone }}
                            </a>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $unit->tenant->phone) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-400 dark:bg-emerald-950/40 transition" title="Enviar WhatsApp">
                                💬 WhatsApp
                            </a>
                        </div>
                    </div>
                @endif

                @if($unit->tenant->email)
                    <div>
                        <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Correo Electrónico</span>
                        <a href="mailto:{{ $unit->tenant->email }}" class="text-sm font-bold text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                            ✉️ {{ $unit->tenant->email }}
                        </a>
                    </div>
                @endif

                <div>
                    <span class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Contrato Inicio</span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">📅 {{ \Carbon\Carbon::parse($unit->tenant->start_date)->format('d M, Y') }}</span>
                </div>

                <div class="sm:col-span-2 border-t border-slate-100 dark:border-slate-700 pt-3">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold text-amber-600 bg-amber-50 dark:text-amber-450 dark:bg-amber-950/20">
                        Día de Pago: Vence el día {{ $unit->tenant->payment_due_day }} de cada mes
                    </span>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State (Vacant) -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 shadow-sm border border-slate-100 dark:border-slate-700 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-full bg-slate-100 dark:bg-slate-700 text-slate-450 flex items-center justify-center text-xl">
                👤
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Unidad Disponible</h3>
                <p class="text-xs text-slate-450 dark:text-slate-500 max-w-xs mx-auto">Esta unidad se encuentra vacía y lista para recibir a un nuevo inquilino.</p>
            </div>
            @can('tenants.create')
                <a href="{{ route('tenants.create', ['unit_id' => $unit->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-semibold text-xs transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Asignar Inquilino
                </a>
            @endcan
        </div>
    @endif

    <!-- Payments Section -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Historial de Pagos</h3>
            @can('payments.create')
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment', houseId: '', unitId: '{{ $unit->id }}'})" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-650 bg-emerald-50 hover:bg-emerald-100 dark:text-emerald-450 dark:bg-emerald-950/40 dark:hover:bg-emerald-900/50 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Pago
            </button>
            @endcan
        </div>

        <div class="space-y-3.5">
            @forelse($unit->payments as $payment)
                <div class="flex items-center justify-between p-3.5 bg-slate-50 dark:bg-slate-900/30 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 flex items-center justify-center text-sm">
                            ✓
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">
                                {{ $payment->type === 'rent' ? 'Renta Mensual' : 'Servicios' }}
                            </h4>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5">📅 Pagado el {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</p>
                            @if($payment->notes)
                                <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-1">📝 {{ $payment->notes }}</p>
                            @endif
                            @if($payment->receipt_url)
                                <a href="{{ $payment->receipt_url }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1">
                                    📎 Ver Comprobante
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-extrabold text-slate-850 dark:text-slate-100">+${{ number_format($payment->amount, 2) }}</span>
                        @if(Auth::user()->isSuperAdmin())
                            <button onclick="Livewire.dispatch('openEditPaymentModal', { paymentId: {{ $payment->id }} })" class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition" title="Editar Pago">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center py-6 text-slate-400 text-xs">No hay historial de pagos registrado para esta unidad.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
