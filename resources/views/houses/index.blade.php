@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Propiedades</h1>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Administra tus casas, departamentos y locales.</p>
        </div>
        
        <a href="{{ route('houses.create') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
            Nueva Propiedad
        </a>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($houses as $house)
            <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm hover:shadow-md border border-slate-100 dark:border-slate-700 flex flex-col group transition duration-200">
                <!-- Cover Image -->
                <div class="h-48 overflow-hidden relative bg-slate-100 dark:bg-slate-700">
                    <img 
                        src="{{ $house->photo_url ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=600&q=80' }}" 
                        alt="{{ $house->name }}" 
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <span class="text-xs font-semibold text-indigo-300 dark:text-indigo-400 uppercase tracking-wider">Propiedad</span>
                        <h3 class="text-lg font-bold text-white mt-0.5">{{ $house->name }}</h3>
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

                        <!-- Button Link -->
                        <a href="{{ route('houses.show', $house) }}" class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-650 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/50 transition">
                            Gestionar
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white dark:bg-slate-800 rounded-3xl p-12 shadow-sm border border-slate-100 dark:border-slate-700 text-center text-slate-400">
                <svg class="w-16 h-16 mx-auto text-slate-300 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200 mb-1">No hay propiedades creadas</h3>
                <p class="text-sm max-w-sm mx-auto mb-6">Comienza registrando tu primera casa o edificio para empezar a administrar.</p>
                <a href="{{ route('houses.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition">
                    Agregar Propiedad
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
