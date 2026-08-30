@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ $unit_id ? route('units.show', $unit_id) : route('tenants.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Registrar Inquilino</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Crea la ficha del inquilino y asígnale su departamento o local.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('tenants.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
            <!-- Full Name -->
            <div>
                <label for="full_name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre Completo del Inquilino</label>
                <input type="text" name="full_name" id="full_name" required placeholder="Juan Pérez" value="{{ old('full_name') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('full_name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Número Celular</label>
                <input type="tel" name="phone" id="phone" placeholder="Ej: 5512345678" value="{{ old('phone') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('phone') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                <input type="email" name="email" id="email" placeholder="ejemplo@correo.com" value="{{ old('email') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Unit assignment -->
            <div>
                <label for="unit_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Departamento / Local a Asignar</label>
                <select name="unit_id" id="unit_id" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-805 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ $unit_id == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->house->name ?? 'Casa' }}) - ${{ number_format($u->base_rent_cost) }}/mes
                        </option>
                    @endforeach
                </select>
                @error('unit_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Payment Due Day -->
            <div>
                <label for="payment_due_day" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Día de Pago Vencimiento (1 - 28)</label>
                <input type="number" name="payment_due_day" id="payment_due_day" min="1" max="28" required placeholder="5" value="{{ old('payment_due_day', 5) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('payment_due_day') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Contract Start Date -->
            <div>
                <label for="start_date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha de Inicio del Contrato</label>
                <input type="date" name="start_date" id="start_date" required value="{{ old('start_date', date('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('start_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Contract End Date -->
            <div>
                <label for="end_date" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha de Fin de Contrato (Opcional)</label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('end_date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Registrar Inquilino
        </button>
    </form>
</div>
@endsection
