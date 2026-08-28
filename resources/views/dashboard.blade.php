@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Welcome Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Resumen Financiero</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Control de ingresos y egresos de tus rentas.</p>
        </div>
        
        <!-- Quick Action Trigger -->
        <div class="flex items-center gap-2">
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment'})" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md font-semibold text-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Pago
            </button>
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'expense'})" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-rose-600 hover:bg-rose-700 shadow-md font-semibold text-sm transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                Registrar Gasto
            </button>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Monthly Income -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ingresos del Mes</p>
                <h3 class="text-2xl font-extrabold text-slate-850 dark:text-slate-100">${{ number_format($incomeMonth, 2) }}</h3>
                <span class="text-xs font-medium text-emerald-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Mensualidad actual
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 11l3-3m0 0l3 3m-3-3v8m0-13a9 9 0 110 18 9 9 0 010-18z" /></svg>
            </div>
        </div>

        <!-- Monthly Expenses -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Gastos del Mes</p>
                <h3 class="text-2xl font-extrabold text-slate-850 dark:text-slate-100">${{ number_format($expensesMonth, 2) }}</h3>
                <span class="text-xs font-medium text-rose-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6" /></svg>
                    Mantenimiento y costos
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" /></svg>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 sm:col-span-2 lg:col-span-1 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Flujo Neto</p>
                <h3 class="text-2xl font-extrabold {{ $netProfitMonth >= 0 ? 'text-indigo-650 dark:text-indigo-400' : 'text-rose-600 dark:text-rose-400' }}">${{ number_format($netProfitMonth, 2) }}</h3>
                <span class="text-xs font-medium text-slate-400">Balance del mes</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
    </div>

    <!-- Properties and Tenants Count Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <span class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $totalHouses }}</span>
            <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-1">Propiedades</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <span class="text-xl font-bold text-slate-800 dark:text-slate-100">{{ $totalUnits }}</span>
            <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-1">Unidades Totales</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <span class="text-xl font-bold text-emerald-500">{{ $occupiedUnits }}</span>
            <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-1">Ocupadas</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-sm border border-slate-100 dark:border-slate-700 text-center">
            <span class="text-xl font-bold text-slate-400">{{ $vacantUnits }}</span>
            <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-1">Vacías / Disp.</p>
        </div>
    </div>

    <!-- Dual Layout Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                Transacciones Recientes
            </h3>
            
            <div class="space-y-4">
                @forelse($recentTransactions as $tx)
                    <div class="flex items-center justify-between p-3.5 hover:bg-slate-50 dark:hover:bg-slate-700/30 rounded-2xl transition duration-150 border border-transparent hover:border-slate-100 dark:hover:border-slate-700/50">
                        <div class="flex items-center gap-4">
                            <!-- Icon -->
                            @if($tx['type'] === 'income')
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                                </div>
                            @endif
                            
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-100">{{ $tx['title'] }}</h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-xs font-semibold text-slate-450">{{ $tx['category'] }}</span>
                                    <span class="text-[10px] text-slate-350">•</span>
                                    <span class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($tx['date'])->format('d M, Y') }}</span>
                                </div>
                                @if($tx['notes'])
                                    <p class="text-[11px] text-slate-450 dark:text-slate-500 mt-1 italic">📝 {{ $tx['notes'] }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <span class="text-sm font-extrabold {{ $tx['type'] === 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                            {{ $tx['type'] === 'income' ? '+' : '-' }}${{ number_format($tx['amount'], 2) }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-650 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-sm">No hay transacciones registradas este mes.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                Rentas Pendientes
            </h3>
            
            <div class="space-y-4">
                @forelse($pendingPayments as $pending)
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-100 dark:border-slate-800 flex flex-col justify-between gap-3">
                        <div>
                            <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $pending['tenant_name'] }}</h4>
                            <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-0.5">{{ $pending['house_name'] }} - {{ $pending['unit_name'] }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800/80 pt-2.5">
                            <span class="text-xs font-semibold text-amber-600 dark:text-amber-450 bg-amber-50 dark:bg-amber-950/20 px-2 py-0.5 rounded-md">
                                Día de pago: Vence el {{ $pending['due_day'] }}
                            </span>
                            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment', houseId: '', unitId: '{{ $pending['unit_id'] }}'})" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                                Cobrar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-emerald-350 dark:text-emerald-700/60 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm">¡Al corriente! No hay rentas pendientes.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
