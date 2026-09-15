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

            <!-- Unit assignment with Search -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="unit-search-input" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        Departamento / Local a Asignar
                    </label>
                    <span id="unit-count-badge" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400">
                        {{ count($units) }} disponibles
                    </span>
                </div>

                <!-- Hidden select for form submission -->
                <select name="unit_id" id="unit_id" required class="sr-only">
                    <option value="">Selecciona una unidad</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ (old('unit_id', $unit_id ?? '') == $u->id) ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->house->name ?? 'Casa' }}) - ${{ number_format($u->base_rent_cost) }}/mes
                        </option>
                    @endforeach
                </select>

                <!-- Search input -->
                <div class="relative mb-2.5">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input 
                        type="text" 
                        id="unit-search-input" 
                        placeholder="Buscar por nombre de unidad o casa..." 
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 pl-10 pr-9 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs focus:outline-none"
                    >
                    <button 
                        type="button" 
                        id="unit-search-clear" 
                        onclick="clearUnitSearch()" 
                        class="hidden absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 text-xs font-bold"
                    >
                        ✕
                    </button>
                </div>

                <!-- Scrollable list of unit cards (mobile friendly) -->
                <div id="unit-options-list" class="max-h-52 overflow-y-auto space-y-1.5 p-1 rounded-2xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/40 dark:bg-slate-900/30">
                    @foreach($units as $u)
                        @php
                            $isSelected = (old('unit_id', $unit_id ?? '') == $u->id);
                        @endphp
                        <div 
                            class="unit-option-card cursor-pointer p-3 rounded-xl border transition-all duration-150 flex items-center justify-between gap-3 {{ $isSelected ? 'bg-indigo-50 border-indigo-300 dark:bg-indigo-950/60 dark:border-indigo-700 ring-2 ring-indigo-500/20' : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700/70 hover:border-indigo-200 dark:hover:border-slate-600' }}"
                            data-id="{{ $u->id }}"
                            data-search="{{ mb_strtolower($u->name . ' ' . ($u->house->name ?? '') . ' ' . $u->base_rent_cost, 'UTF-8') }}"
                            onclick="selectUnitOption({{ $u->id }})"
                        >
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-8 h-8 rounded-lg {{ $isSelected ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-500' }} flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ $u->type === 'commercial' ? '🏪' : '🚪' }}
                                </span>
                                <div class="min-w-0">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block truncate">
                                        {{ $u->name }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block truncate">
                                        📍 {{ $u->house->name ?? 'Casa' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <span class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                                    ${{ number_format($u->base_rent_cost) }}<span class="text-[10px] font-normal text-slate-400">/m</span>
                                </span>
                                <span class="unit-check w-5 h-5 rounded-full {{ $isSelected ? 'bg-indigo-600 text-white flex' : 'border border-slate-300 dark:border-slate-600 hidden' }} items-center justify-center text-[10px] font-bold">
                                    ✓
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <div id="unit-no-results" class="hidden p-6 text-center text-xs text-slate-400">
                        No se encontraron unidades que coincidan con la búsqueda.
                    </div>
                </div>

                @error('unit_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Payment Due Day -->
            <div>
                <label for="payment_due_day" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Día Límite de Pago (1 - 31)</label>
                <input type="number" name="payment_due_day" id="payment_due_day" min="1" max="31" required placeholder="5" value="{{ old('payment_due_day', 5) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">En meses con menos días (como febrero o meses de 30 días), se ajustará automáticamente al último día de ese mes.</p>
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

            <!-- Notes & Schedules -->
            <div>
                <label for="notes" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Notas y Horarios (Opcional)</label>
                <textarea name="notes" id="notes" rows="3" placeholder="Horarios de atención, acuerdos especiales, depósitos, observaciones..." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">{{ old('notes') }}</textarea>
                @error('notes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Registrar Inquilino
        </button>
    </form>
</div>

<script>
    function selectUnitOption(id) {
        const select = document.getElementById('unit_id');
        select.value = id;

        document.querySelectorAll('.unit-option-card').forEach(card => {
            const isTarget = card.getAttribute('data-id') == id;
            const check = card.querySelector('.unit-check');
            const icon = card.querySelector('.w-8');

            if (isTarget) {
                card.className = 'unit-option-card cursor-pointer p-3 rounded-xl border transition-all duration-150 flex items-center justify-between gap-3 bg-indigo-50 border-indigo-300 dark:bg-indigo-950/60 dark:border-indigo-700 ring-2 ring-indigo-500/20';
                if (check) { check.className = 'unit-check w-5 h-5 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[10px] font-bold'; }
                if (icon) { icon.className = 'w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0'; }
            } else {
                card.className = 'unit-option-card cursor-pointer p-3 rounded-xl border transition-all duration-150 flex items-center justify-between gap-3 bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700/70 hover:border-indigo-200 dark:hover:border-slate-600';
                if (check) { check.className = 'unit-check w-5 h-5 rounded-full border border-slate-300 dark:border-slate-600 hidden items-center justify-center text-[10px] font-bold'; }
                if (icon) { icon.className = 'w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-500 flex items-center justify-center font-bold text-xs shrink-0'; }
            }
        });
    }

    const searchInput = document.getElementById('unit-search-input');
    const clearBtn = document.getElementById('unit-search-clear');
    const noResults = document.getElementById('unit-no-results');
    const countBadge = document.getElementById('unit-count-badge');
    const cards = document.querySelectorAll('.unit-option-card');

    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let visibleCount = 0;

            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }

            cards.forEach(card => {
                const text = card.getAttribute('data-search') || '';
                if (!query || text.includes(query)) {
                    card.style.display = 'flex';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }

            if (countBadge) {
                countBadge.textContent = visibleCount + ' disponibles';
            }
        });
    }

    function clearUnitSearch() {
        if (searchInput) {
            searchInput.value = '';
            searchInput.dispatchEvent(new Event('input'));
            searchInput.focus();
        }
    }
</script>
@endsection
