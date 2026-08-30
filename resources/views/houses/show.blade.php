@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('houses.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
            </a>
            <h1 class="text-2xl font-extrabold text-slate-900 dark:text-slate-50 truncate">{{ $house->name }}</h1>
        </div>
        
        <div class="flex items-center gap-2">
            <a href="{{ route('houses.edit', $house) }}" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-xl transition" title="Editar Propiedad">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
            </a>
            
            <form action="{{ route('houses.destroy', $house) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta propiedad y todas sus unidades?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="p-2 text-slate-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition" title="Eliminar Propiedad">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </form>
        </div>
    </div>

    <!-- Big Image Header -->
    <div class="h-64 rounded-3xl overflow-hidden relative shadow-sm border border-slate-100 dark:border-slate-700/50 bg-slate-100 dark:bg-slate-800">
        <img 
            src="{{ $house->photo_url ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80' }}" 
            alt="{{ $house->name }}" 
            class="w-full h-full object-cover"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent"></div>
        <div class="absolute bottom-6 left-6 right-6 text-white space-y-1">
            <h1 class="text-2xl font-extrabold">{{ $house->name }}</h1>
            <p class="text-sm opacity-90 flex items-center gap-1.5">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                {{ $house->address }}
            </p>
        </div>
    </div>

    <!-- Tab Section Container -->
    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
        <!-- Tabs Header -->
        <div class="flex border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 p-2 gap-1">
            <button id="tab-units-btn" onclick="switchTab('units')" class="flex-1 py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 flex items-center justify-center gap-2 text-indigo-600 bg-white shadow-sm dark:text-indigo-400 dark:bg-slate-750">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                Unidades
            </button>
            <button id="tab-expenses-btn" onclick="switchTab('expenses')" class="flex-1 py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 flex items-center justify-center gap-2 text-slate-500 dark:text-slate-450 hover:bg-slate-100/50 dark:hover:bg-slate-700/50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                Gastos
            </button>
            <button id="tab-map-btn" onclick="switchTab('map')" class="flex-1 py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 flex items-center justify-center gap-2 text-slate-500 dark:text-slate-450 hover:bg-slate-100/50 dark:hover:bg-slate-700/50">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                Mapa
            </button>
        </div>

        <!-- Tab Body: Units -->
        <div id="tab-units-content" class="tab-content p-6 space-y-6">
            <div class="space-y-4">
                @forelse($house->units as $unit)
                    <a href="{{ route('units.show', $unit) }}" class="flex items-center justify-between p-4 bg-slate-50 hover:bg-slate-100/70 dark:bg-slate-900/30 dark:hover:bg-slate-700/30 rounded-2xl transition duration-150 border border-slate-100 dark:border-slate-800/80">
                        <div class="flex items-center gap-4">
                            <!-- Avatar icon -->
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold shadow-sm {{ $unit->status === 'occupied' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/30 dark:text-emerald-450' : 'bg-slate-200/50 text-slate-500 dark:bg-slate-850 dark:text-slate-400' }}">
                                @if($unit->type === 'commercial')
                                    🏪
                                @else
                                    🚪
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-slate-850 dark:text-slate-200">{{ $unit->name }}</h4>
                                <p class="text-xs text-slate-450 dark:text-slate-500 font-medium mt-0.5">
                                    Renta: ${{ number_format($unit->base_rent_cost, 2) }}
                                    @if($unit->status === 'occupied' && $unit->tenant)
                                        • Inquilino: {{ $unit->tenant->full_name }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full {{ $unit->status === 'occupied' ? 'text-emerald-600 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/20' : 'text-slate-500 bg-slate-100 dark:text-slate-400 dark:bg-slate-750' }}">
                            {{ $unit->status === 'occupied' ? 'Ocupado' : 'Disponible' }}
                        </span>
                    </a>
                @empty
                    <p class="text-center py-8 text-slate-400 text-sm">Esta propiedad no tiene unidades creadas.</p>
                @endforelse
            </div>

            <!-- Add Unit Button -->
            <a href="{{ route('units.create', ['house_id' => $house->id]) }}" class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-slate-250 hover:border-indigo-400 dark:border-slate-700 dark:hover:border-indigo-500/55 rounded-2xl text-sm font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Agregar Unidad
            </a>
        </div>

        <!-- Tab Body: Expenses -->
        <div id="tab-expenses-content" class="tab-content p-6 space-y-6 hidden">
            <div class="space-y-4">
                @forelse($house->expenses as $expense)
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-900/30 rounded-2xl border border-slate-100 dark:border-slate-800/80">
                        <div>
                            <h4 class="text-sm font-bold text-slate-850 dark:text-slate-200">{{ $expense->type }}</h4>
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium mt-0.5">📅 {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M, Y') }}</p>
                            @if($expense->notes)
                                <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-1">📝 {{ $expense->notes }}</p>
                            @endif
                        </div>
                        <span class="text-sm font-extrabold text-rose-500">-${{ number_format($expense->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-center py-8 text-slate-400 text-sm">No hay gastos registrados en esta propiedad.</p>
                @endforelse
            </div>

            <!-- Add Expense Button -->
            <button onclick="Livewire.dispatch('openTransactionModal', {type: 'expense', houseId: '{{ $house->id }}'})" class="w-full flex items-center justify-center gap-2 py-3 border-2 border-dashed border-slate-250 hover:border-indigo-400 dark:border-slate-700 dark:hover:border-indigo-500/55 rounded-2xl text-sm font-semibold text-slate-500 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Registrar Gasto de Propiedad
            </button>
        </div>

        <!-- Tab Body: Map -->
        <div id="tab-map-content" class="tab-content p-6 hidden">
            @if($house->embed_map_url)
                <div class="space-y-4">
                    <div class="rounded-2xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-inner">
                        <iframe 
                            src="{{ $house->embed_map_url }}" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Ubicación de la propiedad"
                        ></iframe>
                    </div>
                    @if($house->map_url)
                        <div class="flex justify-end">
                            <a href="{{ $house->map_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-slate-700 bg-slate-100 hover:bg-slate-200 dark:text-slate-200 dark:bg-slate-700/80 dark:hover:bg-slate-700 font-semibold text-xs shadow-sm transition duration-200 active:scale-[0.98]">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                Abrir en Google Maps (Enlace externo)
                            </a>
                        </div>
                    @endif
                </div>
            @elseif($house->map_url)
                <div class="space-y-6 max-w-2xl mx-auto p-4 bg-slate-50 dark:bg-slate-900/40 rounded-3xl border border-slate-150 dark:border-slate-800">
                    <div class="text-center space-y-3">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mx-auto text-xl shadow-sm">
                            📍
                        </div>
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200">Enlace de Google Maps Guardado</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Has configurado un enlace estándar para esta propiedad. Puedes abrir el mapa en una pestaña externa o actualizarlo por un mapa integrado (embebido).</p>
                        
                        <a href="{{ $house->map_url }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-indigo-600 to-indigo-550 hover:from-indigo-700 hover:to-indigo-650 font-semibold text-xs shadow-md transition duration-200 active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            Abrir en Google Maps
                        </a>
                    </div>

                    <hr class="border-slate-150 dark:border-slate-800">

                    <div class="space-y-3.5">
                        <h4 class="text-xs font-bold text-slate-700 dark:text-slate-350 flex items-center gap-1.5">
                            💡 ¿Cómo mostrar el mapa embebido aquí adentro?
                        </h4>
                        <ol class="text-xs text-slate-600 dark:text-slate-400 space-y-2.5 list-decimal pl-4 font-medium">
                            <li>Busca tu propiedad en <a href="https://maps.google.com" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">Google Maps</a>.</li>
                            <li>Haz clic en el botón de **"Compartir"** (o "Share").</li>
                            <li>Selecciona la pestaña **"Insertar un mapa"** (o "Embed a map").</li>
                            <li>Haz clic en **"Copiar HTML"**.</li>
                            <li>Copia únicamente la dirección web que se encuentra dentro del atributo `src="..."`.
                                <div class="mt-1.5 p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] text-slate-500 dark:text-slate-455 select-all font-mono break-all leading-relaxed">
                                    Ejemplo de lo que debes copiar:<br>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-semibold">https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!...</span>
                                </div>
                            </li>
                            <li>Edita esta propiedad y pega esa URL en el campo **URL de Mapa Embebido**.</li>
                        </ol>
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-slate-400">
                    <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                    <p class="text-sm">No hay un mapa registrado para esta propiedad.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        
        // Remove active styling from all tab buttons
        const tabs = ['units', 'expenses', 'map'];
        tabs.forEach(t => {
            const btn = document.getElementById(`tab-${t}-btn`);
            btn.className = 'flex-1 py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 flex items-center justify-center gap-2 text-slate-500 dark:text-slate-450 hover:bg-slate-100/50 dark:hover:bg-slate-700/50';
        });

        // Show active content
        document.getElementById(`tab-${tab}-content`).classList.remove('hidden');

        // Add active styling to selected button
        const activeBtn = document.getElementById(`tab-${tab}-btn`);
        activeBtn.className = 'flex-1 py-3 px-4 rounded-2xl font-bold text-sm transition duration-150 flex items-center justify-center gap-2 text-indigo-600 bg-white shadow-sm dark:text-indigo-400 dark:bg-slate-750';
    }
</script>
@endsection
