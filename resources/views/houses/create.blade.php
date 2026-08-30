@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('houses.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Nueva Propiedad</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Registra una propiedad y define sus unidades iniciales.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('houses.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Card: Details -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Detalles de la Propiedad</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre de la Propiedad</label>
                    <input type="text" name="name" id="name" required placeholder="Ej: Edificio Centro, Casa Tec" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Dirección Completa</label>
                    <input type="text" name="address" id="address" required placeholder="Calle, Número, Colonia, Ciudad" value="{{ old('address') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <textarea name="description" id="description" rows="3" placeholder="Descripción corta, referencias, etc." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="photo" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Foto de la Vivienda (Archivo)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('photo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="map_url" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Enlace Externo de Google Maps (Opcional)</label>
                    <input type="url" name="map_url" id="map_url" placeholder="https://maps.app.goo.gl/..." value="{{ old('map_url') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('map_url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="embed_map_url" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">URL de Mapa Embebido (Opcional)</label>
                    <input type="url" name="embed_map_url" id="embed_map_url" placeholder="https://www.google.com/maps/embed?pb=..." value="{{ old('embed_map_url') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('embed_map_url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Card: Units Bulk Creation -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Departamentos / Locales a Crear</h3>
                <button type="button" id="add-unit-btn" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-650 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/50 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                    Agregar Unidad
                </button>
            </div>

            <!-- Dynamic Units Container -->
            <div id="units-container" class="space-y-4">
                <!-- Initial Unit Row -->
                <div class="unit-row p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4 items-end relative">
                    <div class="flex-1 w-full">
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nombre de Unidad</label>
                        <input type="text" name="units[0][name]" required value="Depto 1" placeholder="Ej: Depto 101, Local A" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                    </div>
                    <div class="w-full md:w-44">
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tipo</label>
                        <select name="units[0][type]" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                            <option value="apartment">Departamento</option>
                            <option value="commercial">Local Comercial</option>
                            <option value="house">Casa Independiente</option>
                        </select>
                    </div>
                    <div class="w-full md:w-44">
                        <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Renta Base ($ MXN)</label>
                        <input type="number" name="units[0][base_rent_cost]" required value="4500" placeholder="4500" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                    </div>
                    <div class="w-10 flex items-center justify-center">
                        <!-- Spacer or Delete Button if index > 0 -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Guardar Propiedad y Unidades
        </button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const container = document.getElementById('units-container');
        const addBtn = document.getElementById('add-unit-btn');
        let rowIndex = 1;

        addBtn.addEventListener('click', function () {
            const nextNum = container.querySelectorAll('.unit-row').length + 1;
            const row = document.createElement('div');
            row.className = 'unit-row p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 flex flex-col md:flex-row gap-4 items-end relative';
            row.innerHTML = `
                <div class="flex-1 w-full">
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Nombre de Unidad</label>
                    <input type="text" name="units[${rowIndex}][name]" required value="Depto ${nextNum}" placeholder="Ej: Depto 101, Local A" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                </div>
                <div class="w-full md:w-44">
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Tipo</label>
                    <select name="units[${rowIndex}][type]" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                        <option value="apartment">Departamento</option>
                        <option value="commercial">Local Comercial</option>
                        <option value="house">Casa Independiente</option>
                    </select>
                </div>
                <div class="w-full md:w-44">
                    <label class="block text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5">Renta Base ($ MXN)</label>
                    <input type="number" name="units[${rowIndex}][base_rent_cost]" required value="4000" placeholder="4000" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-3 py-2 bg-white dark:bg-slate-700 text-slate-805 dark:text-slate-100 text-sm">
                </div>
                <div class="w-10 flex items-center justify-center">
                    <button type="button" class="remove-row-btn p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-xl transition focus:outline-none">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            `;
            container.appendChild(row);

            // Bind click handler for delete button
            row.querySelector('.remove-row-btn').addEventListener('click', function () {
                row.remove();
            });

            rowIndex++;
        });
    });
</script>
@endsection
