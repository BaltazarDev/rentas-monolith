<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ config('app.name', 'Control de Rentas') }}</title>
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4f46e5">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Rentas">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    
    <!-- Manifest -->
    <link rel="manifest" href="/site.webmanifest">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="h-full flex flex-col text-slate-800 dark:text-slate-100 overflow-x-hidden pb-16 md:pb-0">
    
    <!-- Main Outer Wrapper -->
    <div class="flex h-full w-full">
        <!-- Sidebar Navigation (Desktop) -->
        <aside class="hidden md:flex flex-col w-64 bg-white dark:bg-slate-800 border-r border-slate-150 dark:border-slate-700 h-screen sticky top-0">
            <!-- Brand Logo -->
            <div class="h-16 flex items-center px-6 border-b border-slate-150 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                <span class="text-xl font-extrabold bg-gradient-to-r from-indigo-600 to-indigo-400 bg-clip-text text-transparent flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    ControlRentas
                </span>
            </div>
            
            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('dashboard') || request()->is('/') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                    Dashboard
                </a>
                
                <a href="/houses" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('houses*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Propiedades
                </a>
                
                <a href="/tenants" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('tenants*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Inquilinos
                </a>
                
                <a href="{{ route('database.backup') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100" onclick="return confirm('¿Estás seguro de que deseas exportar y descargar la base de datos actual?')">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Exportar Base de Datos
                </a>
            </nav>
            
            <!-- User Profile & Logout Section -->
            <div class="p-4 border-t border-slate-150 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-sm font-semibold text-slate-850 dark:text-slate-200 truncate">{{ Auth::user()->name ?? 'Admin' }}</h4>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto relative bg-slate-50 dark:bg-slate-900 pb-12">
            <!-- Top Navbar (Mobile / Tablet Header) -->
            <header class="flex md:hidden items-center justify-between h-16 bg-white dark:bg-slate-800 border-b border-slate-150 dark:border-slate-700 px-6 sticky top-0 z-30 shadow-sm">
                <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-indigo-400 bg-clip-text text-transparent flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    ControlRentas
                </span>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('database.backup') }}" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Exportar Base de Datos" onclick="return confirm('¿Estás seguro de que deseas exportar y descargar la base de datos actual?')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Blade Content Slot -->
            <div class="p-4 md:p-8 max-w-7xl w-full mx-auto flex-1">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-800 dark:text-emerald-355 rounded-2xl flex items-center gap-3 border border-emerald-100 dark:border-emerald-900 shadow-sm animate-fade-in-down">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-950/30 text-rose-800 dark:text-rose-355 rounded-2xl flex items-center gap-3 border border-rose-100 dark:border-rose-900 shadow-sm animate-fade-in-down">
                        <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="font-medium text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot ?? '' }}
                @yield('content')
            </div>
            
            <!-- Global Floating Transaction Buttons -->
            <div class="fixed right-4 bottom-20 md:right-8 md:bottom-8 z-40 flex flex-col gap-2.5">
                <!-- Floating Action Button for payments/expenses -->
                <button onclick="Livewire.dispatch('openTransactionModal', {type: 'payment'})" class="w-12 h-12 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-lg transition-all duration-200 hover:scale-105 active:scale-95 group focus:outline-none" title="Registrar Pago">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                </button>
            </div>
        </main>
    </div>

    <!-- Bottom Navigation Bar (Mobile / Tablet Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-white dark:bg-slate-800 border-t border-slate-150 dark:border-slate-700 flex items-center justify-around px-2 z-40 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
        <a href="/dashboard" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('dashboard') || request()->is('/') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
            Dashboard
        </a>
        
        <a href="/houses" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('houses*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Casas
        </a>
        
        <a href="/tenants" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('tenants*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Inquilinos
        </a>
    </nav>

    <!-- Livewire Transaction Modal Component -->
    @livewire('transaction-modal')

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- PWA Registration Service Worker -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('Service Worker registrado con éxito:', reg.scope))
                    .catch(err => console.error('Error al registrar el Service Worker:', err));
            });
        }
    </script>
</body>
</html>
