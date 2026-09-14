@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Historial Financiero</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Monitorea y filtra los ingresos y egresos registrados en el sistema.</p>
        </div>

        <div class="flex gap-2">
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment'})" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-white bg-emerald-600 hover:bg-emerald-700 shadow-md font-semibold text-sm transition focus:outline-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Pago
            </button>
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'expense'})" class="flex items-center gap-1.5 px-4 py-2.5 rounded-xl text-white bg-rose-600 hover:bg-rose-700 shadow-md font-semibold text-sm transition focus:outline-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Gasto
            </button>
        </div>
    </div>

    <!-- Stats Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Income Card -->
        <div class="bg-gradient-to-br from-emerald-500/10 to-teal-500/5 dark:from-emerald-950/20 dark:to-teal-950/10 border border-emerald-100 dark:border-emerald-900/50 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-450 uppercase tracking-wider">Total Ingresos</span>
                <h2 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100">${{ number_format($totalIncome, 2) }}</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center text-xl shadow-md shadow-emerald-500/25">
                📈
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="bg-gradient-to-br from-rose-500/10 to-red-500/5 dark:from-rose-950/20 dark:to-red-950/10 border border-rose-100 dark:border-rose-900/50 rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold text-rose-600 dark:text-rose-450 uppercase tracking-wider">Total Gastos</span>
                <h2 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100">${{ number_format($totalExpense, 2) }}</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center text-xl shadow-md shadow-rose-500/25">
                📉
            </div>
        </div>

        <!-- Balance Card -->
        <div class="bg-gradient-to-br {{ $netBalance >= 0 ? 'from-indigo-500/10 to-violet-500/5 dark:from-indigo-950/20 dark:to-violet-950/10 border-indigo-100 dark:border-indigo-900/50' : 'from-amber-500/10 to-orange-500/5 dark:from-amber-950/20 dark:to-orange-950/10 border-amber-100 dark:border-amber-900/50' }} border rounded-3xl p-6 shadow-sm flex items-center justify-between">
            <div class="space-y-1">
                <span class="text-xs font-bold {{ $netBalance >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-amber-600 dark:text-amber-400' }} uppercase tracking-wider">Balance Neto</span>
                <h2 class="text-2xl font-extrabold text-slate-805 dark:text-slate-100">${{ number_format($netBalance, 2) }}</h2>
            </div>
            <div class="w-12 h-12 rounded-2xl {{ $netBalance >= 0 ? 'bg-indigo-600' : 'bg-amber-500' }} text-white flex items-center justify-center text-xl shadow-md">
                {{ $netBalance >= 0 ? '⚖️' : '⚠️' }}
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
        <form action="{{ route('transactions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4 items-end">
            <!-- Property Filter -->
            <div>
                <label for="house_id" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Propiedad</label>
                <select name="house_id" id="house_id" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">Todas las Propiedades</option>
                    @foreach($houses as $h)
                        <option value="{{ $h->id }}" {{ $houseId == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Tipo</label>
                <select name="type" id="type" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
                    <option value="">Todos los Movimientos</option>
                    <option value="income" {{ $type === 'income' ? 'selected' : '' }}>Solo Ingresos (Pagos)</option>
                    <option value="expense" {{ $type === 'expense' ? 'selected' : '' }}>Solo Egresos (Gastos)</option>
                </select>
            </div>

            <!-- Start Date -->
            <div>
                <label for="start_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Desde</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate->format('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
            </div>

            <!-- End Date -->
            <div>
                <label for="end_date" class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Hasta</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate->format('Y-m-d') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 text-sm focus:outline-none focus:border-indigo-500">
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-semibold text-xs transition duration-150 shadow-sm focus:outline-none">
                    Filtrar
                </button>
                <a href="{{ route('transactions.index') }}" class="py-2.5 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 dark:bg-slate-700 dark:hover:bg-slate-650 dark:text-slate-300 font-semibold text-xs transition duration-150 focus:outline-none">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-slate-450 dark:text-slate-500 text-[10px] font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                        <th class="px-6 py-4">Movimiento</th>
                        <th class="px-6 py-4">Fecha</th>
                        <th class="px-6 py-4">Propiedad / Unidad</th>
                        <th class="px-6 py-4">Concepto / Notas</th>
                        <th class="px-6 py-4">Comprobante</th>
                        <th class="px-6 py-4 text-right">Monto</th>
                        @if(Auth::user()->isSuperAdmin())
                            <th class="px-6 py-4 text-right">Acción</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80">
                    @forelse($transactions as $tx)
                        <tr class="text-slate-800 dark:text-slate-200 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $tx['tx_type'] === 'income' ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/20' : 'text-rose-700 bg-rose-50 dark:text-rose-450 dark:bg-rose-950/20' }}">
                                    {{ $tx['tx_type'] === 'income' ? 'Ingreso' : 'Egreso' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs font-medium">
                                📅 {{ \Carbon\Carbon::parse($tx['date'])->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-350">
                                {{ $tx['title'] }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold block">{{ $tx['category'] }}</span>
                                @if($tx['notes'])
                                    <span class="text-[10px] text-slate-500 dark:text-slate-450 italic mt-0.5 block max-w-xs truncate" title="{{ $tx['notes'] }}">{{ $tx['notes'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($tx['receipt_url'])
                                    <a href="{{ $tx['receipt_url'] }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-indigo-650 dark:text-indigo-400 hover:underline">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 0A3 3 0 1010.607 15m3.757-5.829l-3.757 3.757m0 0A3 3 0 105.636 18.364M21 21L15 15" /></svg>
                                        Ver Comprobante
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400">Sin archivo</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-extrabold text-sm {{ $tx['tx_type'] === 'income' ? 'text-emerald-600 dark:text-emerald-450' : 'text-rose-600 dark:text-rose-450' }}">
                                {{ $tx['tx_type'] === 'income' ? '+' : '-' }}${{ number_format($tx['amount'], 2) }}
                            </td>
                            @if(Auth::user()->isSuperAdmin())
                                <td class="px-6 py-4 text-right">
                                    @if($tx['tx_type'] === 'income')
                                        <button onclick="Livewire.dispatch('openEditPaymentModal', { paymentId: {{ $tx['id'] }} })" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition" title="Editar Pago">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            Editar
                                        </button>
                                    @else
                                        <span class="text-xs text-slate-300 dark:text-slate-600">-</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isSuperAdmin() ? 7 : 6 }}" class="px-6 py-12 text-center text-slate-400 text-sm font-medium">
                                No se encontraron movimientos en este periodo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-800/20">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
