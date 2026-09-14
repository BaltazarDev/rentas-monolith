@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('houses.show', $house) }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Editar Propiedad</h1>
                <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Actualiza los detalles de "{{ $house->name }}".</p>
            </div>
        </div>

        @if(Auth::check() && Auth::user()->isSuperAdmin())
            <div class="flex items-center gap-2">
                @if($house->is_archived)
                    <form action="{{ route('houses.unarchive', $house) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 rounded-xl transition" title="Restaurar / Activar Propiedad">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.033 8.033 0 01-15.357-2m15.357 2H15" /></svg>
                            Restaurar
                        </button>
                    </form>
                @else
                    <form action="{{ route('houses.archive', $house) }}" method="POST" onsubmit="return confirm('¿Deseas archivar la propiedad \'{{ $house->name }}\'? Ocultará la propiedad pero conservará todo el historial.')">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/40 hover:bg-amber-100 rounded-xl transition" title="Archivar Propiedad">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                            Archivar
                        </button>
                    </form>
                @endif

                <form action="{{ route('houses.destroy', $house) }}" method="POST" onsubmit="return confirm('ADVERTENCIA: ¿Estás seguro de eliminar definitivamente esta propiedad y todas sus unidades asociadas? Esta acción destruirá registros de pagos e inquilinos.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2.5 text-rose-500 hover:text-rose-600 bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/40 dark:hover:bg-rose-900/50 rounded-xl transition" title="Eliminar Propiedad Definitivamente (Super Admin)">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Form -->
    <form action="{{ route('houses.update', $house) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Card: Details -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Detalles de la Propiedad</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre de la Propiedad</label>
                    <input type="text" name="name" id="name" required placeholder="Ej: Edificio Centro, Casa Tec" value="{{ old('name', $house->name) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="address" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Dirección Completa</label>
                    <input type="text" name="address" id="address" required placeholder="Calle, Número, Colonia, Ciudad" value="{{ old('address', $house->address) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('address') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Descripción (Opcional)</label>
                    <textarea name="description" id="description" rows="3" placeholder="Descripción corta, referencias, etc." class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">{{ old('description', $house->description) }}</textarea>
                </div>

                <div>
                    <label for="photo" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Foto de la Vivienda (Subir nueva)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('photo') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="map_url" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Enlace Externo de Google Maps (Opcional)</label>
                    <input type="url" name="map_url" id="map_url" placeholder="https://maps.app.goo.gl/..." value="{{ old('map_url', $house->map_url) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('map_url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="embed_map_url" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">URL de Mapa Embebido (Opcional)</label>
                    <input type="url" name="embed_map_url" id="embed_map_url" placeholder="https://www.google.com/maps/embed?pb=..." value="{{ old('embed_map_url', $house->embed_map_url) }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('embed_map_url') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Actualizar Propiedad
        </button>
    </form>
</div>
@endsection
