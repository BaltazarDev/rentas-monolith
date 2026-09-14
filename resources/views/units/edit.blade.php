@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('units.show', $unit) }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Editar Unidad</h1>
                <p class="text-sm text-slate-555 dark:text-slate-400 mt-1">Modifica los detalles de "{{ $unit->name }}".</p>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->isSuperAdmin())
            <form action="{{ route('units.destroy', $unit) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta unidad/departamento? Esta acción no se puede deshacer y borrará pagos asociados.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2.5 text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 rounded-xl transition" title="Eliminar Unidad (Super Admin)">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        @endif
    </div>

    <!-- Form -->
    <form action="{{ route('units.update', $unit) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
            <!-- Property selection -->
            <div>
                <label for="house_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Propiedad</label>
                <select name="house_id" id="house_id" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-805 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @foreach($houses as $h)
                        <option value="{{ $h->id }}" {{ old('house_id', $unit->house_id) == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
                @error('house_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre/Número de la Unidad</label>
                <input type="text" name="name" id="name" required value="{{ old('name', $unit->name) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipo de Unidad</label>
                <select name="type" id="type" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-805 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="apartment" {{ old('type', $unit->type) === 'apartment' ? 'selected' : '' }}>Departamento</option>
                    <option value="commercial" {{ old('type', $unit->type) === 'commercial' ? 'selected' : '' }}>Local Comercial</option>
                    <option value="house" {{ old('type', $unit->type) === 'house' ? 'selected' : '' }}>Casa Independiente</option>
                </select>
                @error('type') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Rent Cost -->
            <div>
                <label for="base_rent_cost" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Costo de Renta Base ($ MXN)</label>
                <input type="number" step="0.01" name="base_rent_cost" id="base_rent_cost" required value="{{ old('base_rent_cost', $unit->base_rent_cost) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('base_rent_cost') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Estado de Ocupación</label>
                <select name="status" id="status" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-850 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="vacant" {{ old('status', $unit->status) === 'vacant' ? 'selected' : '' }}>Disponible (Vacío)</option>
                    <option value="occupied" {{ old('status', $unit->status) === 'occupied' ? 'selected' : '' }}>Ocupado</option>
                </select>
                @error('status') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Actualizar Unidad
        </button>
    </form>
</div>
@endsection
