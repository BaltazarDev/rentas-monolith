@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header & Month Filter Bar -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Resumen Financiero</h1>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 border border-indigo-100 dark:border-indigo-900/40">
                    📅 {{ $formattedMonthName }}
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Control de ingresos, gastos, gráficas y adeudos de tus propiedades.</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-2">
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment'})" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md font-semibold text-sm transition focus:outline-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Pago
            </button>
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'expense'})" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-rose-600 hover:bg-rose-700 shadow-md font-semibold text-sm transition focus:outline-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4" /></svg>
                Registrar Gasto
            </button>
        </div>
    </div>

    <!-- Month Navigation Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-3 sm:p-4 shadow-sm border border-slate-100 dark:border-slate-700 flex flex-wrap items-center justify-between gap-3">
        <!-- Prev Month Button -->
        <a href="{{ route('dashboard', ['month' => $prevMonth]) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            Mes Anterior
        </a>

        <!-- Month Form Picker -->
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2">
            <label for="month-picker" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hidden sm:inline">Mes a consultar:</label>
            <input type="month" id="month-picker" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()" class="rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-1.5 bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 text-xs font-bold shadow-sm focus:border-indigo-500 focus:outline-none">
            @if(!$isCurrentMonth)
                <a href="{{ route('dashboard') }}" class="px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 transition">
                    Mes Actual
                </a>
            @endif
        </form>

        <!-- Next Month Button -->
        <a href="{{ route('dashboard', ['month' => $nextMonth]) }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
            Mes Siguiente
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
        </a>
    </div>

    <!-- Summary Metrics Cards (4 columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Monthly Income -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ingresos del Mes</p>
                <h3 class="text-2xl font-extrabold text-slate-850 dark:text-slate-100">${{ number_format($incomeMonth, 2) }}</h3>
                <span class="text-xs font-medium text-emerald-500 flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Cobrado
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
                    Egresos
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z" /></svg>
            </div>
        </div>

        <!-- Net Profit -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Flujo Neto</p>
                <h3 class="text-2xl font-extrabold {{ $netProfitMonth >= 0 ? 'text-indigo-650 dark:text-indigo-400' : 'text-rose-600 dark:text-rose-400' }}">${{ number_format($netProfitMonth, 2) }}</h3>
                <span class="text-xs font-medium text-slate-400">Balance del periodo</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/30 text-indigo-650 dark:text-indigo-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>

        <!-- Monthly Debts (Adeudos del Mes) -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Adeudos del Mes</p>
                <h3 class="text-2xl font-extrabold {{ $totalDebtsMonth > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                    ${{ number_format($totalDebtsMonth, 2) }}
                </h3>
                <span class="text-xs font-semibold {{ $totalDebtsMonth > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }} flex items-center gap-1">
                    @if($totalDebtsMonth > 0)
                        ⚠️ {{ count($pendingPayments) }} {{ count($pendingPayments) === 1 ? 'inquilino pendiente' : 'inquilinos pendientes' }}
                    @else
                        ✅ 0 adeudos registrados
                    @endif
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Interactive Charts Section (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart 1: Daily Income vs Expenses -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 flex items-center gap-2">
                        <span>📊</span> Ingresos vs Gastos Diarios
                    </h3>
                    <p class="text-xs text-slate-450 dark:text-slate-400 mt-0.5">Evolución día por día durante {{ $formattedMonthName }}.</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> Ingresos
                    </span>
                    <span class="flex items-center gap-1 text-rose-500">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span> Gastos
                    </span>
                </div>
            </div>
            <div class="relative h-64 w-full">
                <canvas id="dailyFinancialChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Income by Property / House -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <div>
                <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 flex items-center gap-2">
                    <span>🏠</span> Ingresos por Propiedad
                </h3>
                <p class="text-xs text-slate-450 dark:text-slate-400 mt-0.5">Distribución en {{ $formattedMonthName }}.</p>
            </div>
            <div class="relative h-64 w-full flex items-center justify-center">
                @if(count($houseIncomes) > 0)
                    <canvas id="houseDistributionChart"></canvas>
                @else
                    <div class="text-center text-slate-400 text-xs py-10 space-y-1">
                        <span class="text-3xl block">🏘️</span>
                        <p>No hay ingresos registrados para este mes.</p>
                    </div>
                @endif
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

    <!-- Dual Layout Section: Transactions & Adeudos -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                    Movimientos de {{ $formattedMonthName }}
                </h3>
                <a href="{{ route('transactions.index', ['start_date' => $selectedMonth . '-01', 'end_date' => $selectedMonth . '-' . Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->daysInMonth]) }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                    Ver Todos
                </a>
            </div>
            
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
                        
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-extrabold {{ $tx['type'] === 'income' ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $tx['type'] === 'income' ? '+' : '-' }}${{ number_format($tx['amount'], 2) }}
                            </span>
                            @if(Auth::user()->isSuperAdmin() && $tx['type'] === 'income')
                                <button onclick="Livewire.dispatch('openEditPaymentModal', { paymentId: {{ $tx['id'] }} })" class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Editar Pago (Super Admin)">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-650 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="text-sm">No hay transacciones registradas en este mes.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Debts & Pending Payments of Month (Adeudos por Mes) -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    Adeudos del Mes
                </h3>
                @if(count($pendingPayments) > 0)
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                        {{ count($pendingPayments) }} pendientes
                    </span>
                @endif
            </div>
            
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-1">
                @forelse($pendingPayments as $pending)
                    <div class="p-4 bg-slate-50 dark:bg-slate-900/40 rounded-2xl border border-slate-100 dark:border-slate-800 flex flex-col justify-between gap-3">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $pending['tenant_name'] }}</h4>
                                <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-0.5">{{ $pending['house_name'] }} - {{ $pending['unit_name'] }}</p>
                            </div>
                            <!-- Status badge -->
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-md shrink-0 {{ $pending['status'] === 'overdue' ? 'bg-rose-100 text-rose-700 dark:bg-rose-950/40 dark:text-rose-400' : ($pending['status'] === 'partial' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400') }}">
                                {{ $pending['status'] === 'overdue' ? 'Vencido' : ($pending['status'] === 'partial' ? 'Abono Parcial' : 'Por Vencer') }}
                            </span>
                        </div>

                        <!-- Debt amount calculation -->
                        <div class="bg-white dark:bg-slate-800/80 p-2.5 rounded-xl flex items-center justify-between text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10px]">Renta: ${{ number_format($pending['rent_cost']) }}</span>
                                @if($pending['paid_amount'] > 0)
                                    <span class="text-emerald-500 block text-[10px]">Abonó: ${{ number_format($pending['paid_amount']) }}</span>
                                @endif
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 block uppercase font-bold">Saldo Adeudado</span>
                                <span class="text-sm font-extrabold text-rose-600 dark:text-rose-400">
                                    ${{ number_format($pending['debt_amount'], 2) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between border-t border-slate-100 dark:border-slate-800/80 pt-2.5">
                            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                                Vence: día {{ $pending['due_day'] }}
                            </span>
                            <div class="flex items-center gap-2">
                                @if($pending['phone'])
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pending['phone']) }}" target="_blank" class="px-2 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/40 dark:text-emerald-400 transition" title="Enviar recordatorio WhatsApp">
                                        💬 WhatsApp
                                    </a>
                                @endif
                                <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment', houseId: '', unitId: '{{ $pending['unit_id'] }}'})" class="px-3 py-1 rounded-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition">
                                    Cobrar
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <svg class="w-10 h-10 mx-auto text-emerald-350 dark:text-emerald-700/60 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">¡Al corriente!</p>
                        <p class="text-xs text-slate-400 mt-1">No hay adeudos de renta pendientes en {{ $formattedMonthName }}.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(226, 232, 240, 0.6)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        // 1. Daily Financial Chart (Income vs Expenses)
        const dailyCtx = document.getElementById('dailyFinancialChart');
        if (dailyCtx) {
            new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Ingresos',
                            data: {!! json_encode($chartIncomeData) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.85)',
                            borderRadius: 6,
                            borderSkipped: false,
                        },
                        {
                            label: 'Gastos',
                            data: {!! json_encode($chartExpenseData) !!},
                            backgroundColor: 'rgba(244, 63, 94, 0.85)',
                            borderRadius: 6,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: textColor, font: { size: 10 } }
                        },
                        y: {
                            grid: { color: gridColor },
                            ticks: {
                                color: textColor,
                                font: { size: 10 },
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. House Distribution Chart (Doughnut)
        const houseCtx = document.getElementById('houseDistributionChart');
        if (houseCtx) {
            const houseLabels = {!! json_encode($houseLabels) !!};
            const houseIncomes = {!! json_encode($houseIncomes) !!};

            if (houseLabels.length > 0) {
                new Chart(houseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: houseLabels,
                        datasets: [{
                            data: houseIncomes,
                            backgroundColor: [
                                '#6366f1', '#10b981', '#f59e0b', '#ec4899', 
                                '#06b6d4', '#8b5cf6', '#3b82f6', '#14b8a6'
                            ],
                            borderWidth: 2,
                            borderColor: isDark ? '#1e293b' : '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    color: textColor,
                                    font: { size: 11 },
                                    boxWidth: 12,
                                    padding: 12
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let val = context.parsed;
                                        return label + ': ' + new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(val);
                                    }
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }
        }
    });
</script>
@endsection
