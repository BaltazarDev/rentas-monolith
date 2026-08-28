@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('houses.show', $house_id ?: 1) }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Nueva Unidad</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Registra un departamento, cuarto o local comercial.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('units.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
            <!-- Property selection -->
            <div>
                <label for="house_id" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Propiedad</label>
                <select name="house_id" id="house_id" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-805 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @foreach($houses as $h)
                        <option value="{{ $h->id }}" {{ $house_id == $h->id ? 'selected' : '' }}>{{ $h->name }}</option>
                    @endforeach
                </select>
                @error('house_id') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre/Número de la Unidad</label>
                <input type="text" name="name" id="name" required placeholder="Ej: Depto 101, Local B" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Type -->
            <div>
                <label for="type" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipo de Unidad</label>
                <select name="type" id="type" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-805 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="apartment">Departamento</option>
                    <option value="commercial">Local Comercial</option>
                    <option value="house">Casa Independiente</option>
                </select>
                @error('type') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Rent Cost -->
            <div>
                <label for="base_rent_cost" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Costo de Renta Base ($ MXN)</label>
                <input type="number" step="0.01" name="base_rent_cost" id="base_rent_cost" required placeholder="5000" value="{{ old('base_rent_cost') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('base_rent_cost') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Guardar Unidad
        </button>
    </form>
</div>
@endsection
