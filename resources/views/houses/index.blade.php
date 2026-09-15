@extends('layouts.app')

@section('content')
<div class="space-y-6 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Propiedades</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Administra tus casas, departamentos y locales comerciales.</p>
        </div>
        
        @can('houses.create')
            <a href="{{ route('houses.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Nueva Propiedad
            </a>
        @endcan
    </div>

    @can('houses.delete')
        <!-- Status Tabs (Activas vs Archivadas) -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
            <a href="{{ route('houses.index', ['status' => 'active']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition {{ $status !== 'archived' ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Activas
                <span class="text-xs px-2 py-0.5 rounded-full {{ $status !== 'archived' ? 'bg-indigo-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">{{ $activeCount }}</span>
            </a>
            
            <a href="{{ route('houses.index', ['status' => 'archived']) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold transition {{ $status === 'archived' ? 'bg-amber-600 text-white shadow-sm' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                Archivadas
                <span class="text-xs px-2 py-0.5 rounded-full {{ $status === 'archived' ? 'bg-amber-500 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">{{ $archivedCount }}</span>
            </a>
        </div>
    @endcan

    @if($status === 'archived')
        <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800/60 rounded-2xl p-4 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-amber-600 dark:text-amber-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                <p class="text-xs sm:text-sm text-amber-900 dark:text-amber-200 font-medium">
                    Estas propiedades están archivadas. Sus unidades, contratos de inquilinos, pagos y gastos se conservan íntegros.
                </p>
            </div>
        </div>
    @endif

    <!-- Search & Filter Controls -->
    <div class="space-y-3">
        <div class="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
            <!-- Search Input -->
            <div class="relative flex-1 max-w-lg">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input 
                    type="text" 
                    id="house-search" 
                    placeholder="Buscar por nombre, dirección o descripción..." 
                    class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 pl-11 pr-10 py-2.5 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm focus:outline-none transition"
                >
                <button 
                    id="clear-search-btn" 
                    type="button" 
                    class="hidden absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                    title="Limpiar búsqueda"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Results Counter -->
            <div class="text-xs text-slate-450 dark:text-slate-400 font-medium self-end md:self-auto">
                Mostrando <span id="visible-count" class="font-bold text-slate-700 dark:text-slate-200">{{ $houses->count() }}</span> de {{ $houses->count() }} propiedades
            </div>
        </div>

        <!-- Filter Chips -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs">
            <button type="button" onclick="setFilter('all')" data-filter="all" class="filter-chip px-3.5 py-1.5 rounded-xl font-bold transition duration-150 bg-indigo-600 text-white shadow-sm">
                Todas ({{ $houses->count() }})
            </button>
            <button type="button" onclick="setFilter('available')" data-filter="available" class="filter-chip px-3.5 py-1.5 rounded-xl font-medium transition duration-150 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750">
                🟢 Con disponibles
            </button>
            <button type="button" onclick="setFilter('full')" data-filter="full" class="filter-chip px-3.5 py-1.5 rounded-xl font-medium transition duration-150 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750">
                🔵 100% Ocupadas
            </button>
            <button type="button" onclick="setFilter('vacant')" data-filter="vacant" class="filter-chip px-3.5 py-1.5 rounded-xl font-medium transition duration-150 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750">
                ⚪ Totalmente vacías
            </button>
        </div>
    </div>

    <!-- Properties Grid -->
    <div id="houses-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($houses as $house)
            @php
                $vacantCount = $house->total_units_count - $house->occupied_units_count;
            @endphp
            <div 
                onclick="window.location.href='{{ route('houses.show', $house) }}'"
                class="house-card cursor-pointer bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 border border-slate-100 dark:border-slate-700 flex flex-col group transition duration-200 {{ $house->is_archived ? 'opacity-90 ring-1 ring-amber-400/50' : '' }}"
                data-name="{{ mb_strtolower($house->name) }}"
                data-address="{{ mb_strtolower($house->address) }}"
                data-description="{{ mb_strtolower($house->description ?? '') }}"
                data-total="{{ $house->total_units_count }}"
                data-occupied="{{ $house->occupied_units_count }}"
                data-vacant="{{ $vacantCount }}"
            >
                <!-- Cover Image -->
                <div class="h-48 overflow-hidden relative bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
                    @if($house->photo_url)
                        <img 
                            src="{{ $house->photo_url }}" 
                            alt="{{ $house->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');"
                        >
                    @endif
                    <div class="{{ $house->photo_url ? 'hidden ' : '' }}w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-slate-200 via-slate-250 to-slate-300 dark:from-slate-750 dark:via-slate-800 dark:to-slate-850 text-slate-500 dark:text-slate-400 group-hover:scale-105 transition duration-500">
                        <svg class="w-12 h-12 mb-1.5 opacity-60 group-hover:scale-110 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[10px] font-bold tracking-wider uppercase opacity-75">Sin fotografía</span>
                    </div>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent pointer-events-none"></div>
                    
                    @if($house->is_archived)
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-black uppercase tracking-wider bg-amber-500/90 text-white shadow-md backdrop-blur-sm">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                                Archivada
                            </span>
                        </div>
                    @endif

                    <div class="absolute bottom-4 left-4 right-4 pointer-events-none">
                        <span class="text-xs font-semibold text-indigo-300 dark:text-indigo-400 uppercase tracking-wider">Propiedad</span>
                        <h3 class="text-lg font-bold text-white mt-0.5 group-hover:text-indigo-200 transition-colors">{{ $house->name }}</h3>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-6 flex-1 flex flex-col justify-between gap-4">
                    <div class="space-y-3">
                        <p class="text-xs text-slate-500 dark:text-slate-400 flex items-start gap-1.5 leading-relaxed">
                            <svg class="w-4 h-4 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $house->address }}
                        </p>
                        
                        <!-- Description -->
                        @if($house->description)
                            <p class="text-xs text-slate-450 dark:text-slate-500 line-clamp-2 leading-relaxed">{{ $house->description }}</p>
                        @endif
                    </div>

                    <!-- Occupancy & Actions -->
                    <div class="border-t border-slate-100 dark:border-slate-700/80 pt-4 flex items-center justify-between">
                        <!-- Occupancy rate -->
                        <div class="flex flex-col gap-0.5">
                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Ocupación</span>
                            <span class="text-xs font-bold text-slate-800 dark:text-slate-200">
                                {{ $house->occupied_units_count }} / {{ $house->total_units_count }} Unidades
                            </span>
                        </div>

                        <!-- Actions & Arrow Indicator -->
                        <div class="flex items-center gap-2">
                            @if(Auth::check() && Auth::user()->isSuperAdmin())
                                <div class="flex items-center gap-1" onclick="event.stopPropagation()">
                                    @if($house->is_archived)
                                        <!-- Restaurar / Desarchivar -->
                                        <form action="{{ route('houses.unarchive', $house) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 rounded-xl transition" title="Restaurar y Activar Propiedad">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.033 8.033 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Archivar -->
                                        <form action="{{ route('houses.archive', $house) }}" method="POST" onsubmit="return confirm('¿Deseas archivar la propiedad \'{{ $house->name }}\'?\n\nSe ocultará del catálogo activo pero CONSERVARÁ todo su historial: contratos, inquilinos, cobros y gastos.')">
                                            @csrf
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40 rounded-xl transition" title="Archivar Propiedad (Conservar historial)">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Eliminar definitivamente (Solo Super Admin) -->
                                    <form action="{{ route('houses.destroy', $house) }}" method="POST" onsubmit="return confirm('ADVERTENCIA: ¿Estás seguro de eliminar definitivamente \'{{ $house->name }}\' y todas sus unidades asociadas?\n\nEsta acción destruirá registros de pagos e inquilinos. Si deseas conservar los datos históricos, se recomienda ARCHIVAR.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-xl transition" title="Eliminar Definitivamente (Super Admin)">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            <div class="w-8 h-8 rounded-xl bg-slate-50 dark:bg-slate-750 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-950/50 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                                <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-800 rounded-3xl p-12 shadow-sm border border-slate-100 dark:border-slate-700 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">
                    {{ $status === 'archived' ? 'No hay propiedades archivadas' : 'No hay propiedades activas' }}
                </h3>
                <p class="text-sm max-w-sm mx-auto mb-6">
                    {{ $status === 'archived' ? 'Las propiedades que archives para resguardar su historial aparecerán aquí.' : 'Comienza registrando tu primera casa o edificio para empezar a administrar.' }}
                </p>
                @if($status !== 'archived')
                    <a href="{{ route('houses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition">
                        Agregar Propiedad
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Empty Filter Result Message -->
    <div id="no-filter-results" class="hidden bg-white dark:bg-slate-800 rounded-3xl p-12 shadow-sm border border-slate-100 dark:border-slate-700 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <h3 class="text-base font-bold text-slate-800 dark:text-slate-200 mb-1">No se encontraron propiedades</h3>
        <p class="text-xs text-slate-450 dark:text-slate-500 max-w-sm mx-auto mb-4">No hay propiedades que coincidan con la búsqueda o filtro seleccionado.</p>
        <button type="button" onclick="resetFilters()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/40 hover:bg-indigo-100 transition">
            Limpiar filtros
        </button>
    </div>
</div>

<script>
    let currentFilter = 'all';
    const searchInput = document.getElementById('house-search');
    const clearBtn = document.getElementById('clear-search-btn');
    const cards = document.querySelectorAll('.house-card');
    const visibleCountEl = document.getElementById('visible-count');
    const noResultsEl = document.getElementById('no-filter-results');

    function applyFilterAndSearch() {
        const query = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let visibleCount = 0;

        if (clearBtn) {
            if (query.length > 0) {
                clearBtn.classList.remove('hidden');
            } else {
                clearBtn.classList.add('hidden');
            }
        }

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const address = card.getAttribute('data-address') || '';
            const description = card.getAttribute('data-description') || '';
            const total = parseInt(card.getAttribute('data-total') || '0', 10);
            const occupied = parseInt(card.getAttribute('data-occupied') || '0', 10);
            const vacant = parseInt(card.getAttribute('data-vacant') || '0', 10);

            // Check search match
            const matchesSearch = !query || name.includes(query) || address.includes(query) || description.includes(query);

            // Check filter match
            let matchesFilter = true;
            if (currentFilter === 'available') {
                matchesFilter = vacant > 0;
            } else if (currentFilter === 'full') {
                matchesFilter = total > 0 && occupied === total;
            } else if (currentFilter === 'vacant') {
                matchesFilter = occupied === 0;
            }

            if (matchesSearch && matchesFilter) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (visibleCountEl) {
            visibleCountEl.textContent = visibleCount;
        }

        if (noResultsEl) {
            if (visibleCount === 0 && cards.length > 0) {
                noResultsEl.classList.remove('hidden');
            } else {
                noResultsEl.classList.add('hidden');
            }
        }
    }

    function setFilter(filter) {
        currentFilter = filter;
        document.querySelectorAll('.filter-chip').forEach(btn => {
            const f = btn.getAttribute('data-filter');
            if (f === filter) {
                btn.className = 'filter-chip px-3.5 py-1.5 rounded-xl font-bold transition duration-150 bg-indigo-600 text-white shadow-sm';
            } else {
                btn.className = 'filter-chip px-3.5 py-1.5 rounded-xl font-medium transition duration-150 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-750';
            }
        });
        applyFilterAndSearch();
    }

    function resetFilters() {
        if (searchInput) searchInput.value = '';
        setFilter('all');
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilterAndSearch);
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            applyFilterAndSearch();
            if (searchInput) searchInput.focus();
        });
    }
</script>
@endsection
