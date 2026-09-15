<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <script>
        (function () {
            const getCookie = (name) => {
                const val = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
                return val ? val.pop() : null;
            };
            const theme = getCookie('theme');
            if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ config('app.name', 'Control de Rentas') }}</title>
    
    <!-- PWA & Standalone Mode Meta Tags -->
    <meta name="theme-color" content="#4f46e5" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Control de Rentas">
    <meta name="application-name" content="Control de Rentas">
    <meta name="msapplication-TileColor" content="#4f46e5">
    <meta name="msapplication-TileImage" content="/icons/icon-144x144.png">
    
    <!-- PWA Manifest & Icons -->
    <link rel="manifest" href="/site.webmanifest?v=2">
    <link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icons/icon-152x152.png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <style>
        :root {
            --sat: env(safe-area-inset-top, 0px);
            --sab: env(safe-area-inset-bottom, 0px);
            --sal: env(safe-area-inset-left, 0px);
            --sar: env(safe-area-inset-right, 0px);
        }
        body {
            font-family: 'Outfit', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        .safe-pb {
            padding-bottom: calc(var(--sab) + 4.5rem);
        }
        .safe-bottom-nav {
            padding-bottom: var(--sab);
            height: calc(4rem + var(--sab));
        }
        .safe-header {
            padding-top: var(--sat);
            height: calc(4rem + var(--sat));
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
                @can('dashboard.view')
                    <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('dashboard') || request()->is('/') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                        Dashboard
                    </a>
                @endcan
                
                @can('houses.view')
                <a href="/houses" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('houses*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    Propiedades
                </a>
                @endcan
                
                @can('tenants.view')
                <a href="/tenants" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('tenants*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Inquilinos
                </a>
                @endcan

                @can('transactions.view')
                    <a href="/transactions" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('transactions*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Historial Financiero
                    </a>
                @endcan
                
                @can('users.manage')
                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('users*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        Usuarios y Accesos
                    </a>
                @endcan

                @can('import.data')
                    <a href="/import" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 {{ request()->is('import*') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400' : 'text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        Carga Masiva (Excel)
                    </a>
                @endcan
                    
                @can('database.backup')
                    <a href="{{ route('database.backup') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl font-medium transition duration-200 text-slate-500 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-800 dark:hover:text-slate-100" onclick="return confirm('¿Estás seguro de que deseas exportar y descargar la base de datos actual?')">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Exportar Base de Datos
                    </a>
                @endcan
            </nav>
            
            <!-- User Profile & Logout Section -->
            <div class="p-4 border-t border-slate-150 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full {{ Auth::user()->isSuperAdmin() ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400' : (Auth::user()->isOperator() ? 'bg-emerald-500/20 text-emerald-600 dark:text-emerald-400' : (Auth::user()->role === 'custom' ? 'bg-purple-500/20 text-purple-600 dark:text-purple-400' : 'bg-indigo-600 text-white')) }} flex items-center justify-center font-bold text-sm">
                            {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="text-sm font-semibold text-slate-850 dark:text-slate-200 truncate">{{ Auth::user()->name ?? 'Admin' }}</h4>
                            <p class="text-[10px] font-bold {{ Auth::user()->isSuperAdmin() ? 'text-amber-600 dark:text-amber-400' : (Auth::user()->isOperator() ? 'text-emerald-600 dark:text-emerald-400' : (Auth::user()->role === 'custom' ? 'text-purple-600 dark:text-purple-400' : 'text-slate-400')) }}">
                                {{ Auth::user()->isSuperAdmin() ? '👑 Super Admin' : (Auth::user()->isOperator() ? '📋 Operador' : (Auth::user()->role === 'custom' ? '⚙️ Personalizado' : '🛡️ Admin')) }}
                            </p>
                            <p class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-1">
                        <button onclick="toggleDarkMode()" class="p-2 text-slate-400 hover:text-indigo-600 dark:hover:text-amber-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700/50 transition duration-150 focus:outline-none" title="Cambiar tema">
                            <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            <svg class="w-5 h-5 block dark:hidden text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>
                        
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-500 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 01-3-3h4a3 3 0 013 3v1" /></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-h-screen overflow-y-auto relative bg-slate-50 dark:bg-slate-900 safe-pb">
            <!-- Top Navbar (Mobile / Tablet Header) -->
            <header class="flex md:hidden items-center justify-between safe-header bg-white dark:bg-slate-800 border-b border-slate-150 dark:border-slate-700 px-6 sticky top-0 z-30 shadow-sm">
                <span class="text-lg font-bold bg-gradient-to-r from-indigo-600 to-indigo-400 bg-clip-text text-transparent flex items-center gap-1.5">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    ControlRentas
                </span>
                
                <div class="flex items-center gap-1">
                    <!-- PWA Install Button (Mobile Header) -->
                    <button type="button" onclick="installPwa()" class="pwa-install-btn hidden p-2 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition" title="Instalar Aplicación">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </button>

                    <button onclick="toggleDarkMode()" class="p-2 text-slate-400 hover:text-indigo-650 dark:hover:text-amber-400 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700/50 transition duration-150 focus:outline-none" title="Cambiar tema">
                        <svg class="w-5 h-5 hidden dark:block text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        <svg class="w-5 h-5 block dark:hidden text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    @can('transactions.view')
                        <!-- Add Transaction index on mobile -->
                        <a href="/transactions" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Historial Financiero">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </a>
                    @endcan

                    @can('users.manage')
                        <!-- Add Users on mobile -->
                        <a href="{{ route('users.index') }}" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Usuarios y Accesos">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </a>
                    @endcan

                    @can('import.data')
                        <!-- Add Import on mobile -->
                        <a href="/import" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Carga Masiva (Excel)">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        </a>
                    @endcan

                    @can('database.backup')
                        <a href="{{ route('database.backup') }}" class="p-2 text-slate-400 hover:text-indigo-600 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-950/40 transition" title="Exportar Base de Datos" onclick="return confirm('¿Estás seguro de que deseas exportar y descargar la base de datos actual?')">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        </a>
                    @endcan
                    
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
        </main>
    </div>

    <!-- Global Floating Transaction Buttons -->
    <div class="fixed right-4 bottom-20 md:right-8 md:bottom-8 z-40 flex flex-col items-end gap-2.5 pointer-events-auto">
        @can('expenses.create')
        <!-- Botón Registrar Gasto de Propiedad (Egreso) -->
        <button 
            type="button"
            onclick="openTransactionModal('expense')" 
            class="flex items-center gap-2.5 px-4 py-3 rounded-full bg-rose-600 hover:bg-rose-700 active:scale-95 text-white shadow-xl shadow-rose-600/35 border border-rose-500/50 transition-all duration-200 group focus:outline-none cursor-pointer select-none"
            title="Registrar Gasto de Propiedad"
        >
            <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H6" />
            </svg>
            <span class="text-xs font-bold tracking-wide">Registrar Gasto</span>
        </button>
        @endcan

        @can('payments.create')
        <!-- Botón Registrar Cobro / Ingreso de Renta -->
        <button 
            type="button"
            onclick="openTransactionModal('payment')" 
            class="flex items-center gap-2.5 px-4 py-3 rounded-full bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white shadow-xl shadow-emerald-600/35 border border-emerald-500/50 transition-all duration-200 group focus:outline-none cursor-pointer select-none"
            title="Registrar Ingreso de Renta"
        >
            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span class="text-xs font-bold tracking-wide">Cobrar Renta</span>
        </button>
        @endcan
    </div>

    <!-- Bottom Navigation Bar (Mobile / Tablet Only) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 safe-bottom-nav bg-white dark:bg-slate-800 border-t border-slate-150 dark:border-slate-700 flex items-center justify-around px-2 z-40 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
        @can('dashboard.view')
            <a href="/dashboard" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('dashboard') || request()->is('/') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                Dashboard
            </a>
        @endcan
        
        @can('houses.view')
        <a href="/houses" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('houses*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
            Propiedades
        </a>
        @endcan
        
        @can('tenants.view')
        <a href="/tenants" class="flex flex-col items-center gap-0.5 text-xs font-semibold px-4 py-2 transition {{ request()->is('tenants*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            Inquilinos
        </a>
        @endcan
    </nav>

    <!-- Modal informativo para iOS Safari -->
    <div id="pwa-ios-modal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-end sm:items-center justify-center p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-sm w-full p-6 shadow-2xl border border-slate-150 dark:border-slate-700 text-center animate-fade-in">
            <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-2">Instalar en iPhone / iPad</h3>
            <p class="text-sm text-slate-600 dark:text-slate-300 mb-4 leading-relaxed">
                Para usar la app sin la barra de navegación:
            </p>
            <div class="text-left bg-slate-50 dark:bg-slate-900/50 p-3.5 rounded-2xl text-xs space-y-2 text-slate-600 dark:text-slate-300 mb-5 border border-slate-150 dark:border-slate-700/50">
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0">1</span>
                    <span>Toca el botón <strong>Compartir</strong> <svg class="inline w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg> en Safari.</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0">2</span>
                    <span>Selecciona <strong>"Agregar al inicio"</strong>.</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded-full bg-indigo-600 text-white font-bold flex items-center justify-center text-[10px] shrink-0">3</span>
                    <span>Abre la app desde tu pantalla de inicio sin navegador.</span>
                </div>
            </div>
            <button onclick="document.getElementById('pwa-ios-modal').classList.add('hidden')" class="w-full py-2.5 px-4 rounded-xl font-semibold text-sm text-white bg-indigo-600 hover:bg-indigo-700 transition">
                Entendido
            </button>
        </div>
    </div>

    <!-- Livewire Transaction Modal Component -->
    @livewire('transaction-modal')
    @livewire('edit-payment-modal')

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- PWA Registration & Install Handler -->
    <script>
        // Registrar Service Worker con forzado de actualización
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js?v=3')
                    .then(reg => {
                        console.log('PWA Service Worker registrado:', reg.scope);
                        // Forzar comprobación de actualización de caché
                        reg.update();
                    })
                    .catch(err => console.error('Error al registrar Service Worker:', err));
            });
        }

        // Manejo de Instalación PWA (Modo Standalone / App)
        let deferredPrompt = null;
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;
        const isIos = /iphone|ipad|ipod/.test(window.navigator.userAgent.toLowerCase());

        // Mostrar botones de instalación por defecto si no estamos dentro de la app standalone
        if (!isStandalone) {
            document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.remove('hidden'));
        }

        // Capturar evento antes de instalación en Chrome / Edge
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (!isStandalone) {
                document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.remove('hidden'));
            }
        });

        function installPwa() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('Usuario aceptó la instalación');
                        document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.add('hidden'));
                    }
                    deferredPrompt = null;
                });
            } else if (isIos) {
                document.getElementById('pwa-ios-modal').classList.remove('hidden');
            } else {
                // Si el navegador ya la tiene instalada o no ha disparado el prompt
                alert('💡 Cómo instalar o abrir la app:\n\n1. En Google Chrome o Edge en tu PC: Busca el icono ⊕ o monitor a la derecha de la barra de direcciones (o menú ⋮ > "Instalar Control de Rentas").\n2. Si ya la instalaste anteriormente, búscala en tu menú de Inicio de Windows como "Control de Rentas".\n3. En teléfonos: Abre el menú ⋮ del navegador y selecciona "Instalar aplicación".');
            }
        }

        window.addEventListener('appinstalled', () => {
            console.log('PWA instalada con éxito');
            document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.add('hidden'));
        });

        function openTransactionModal(type, houseId = '', unitId = '') {
            const comp = window.Livewire?.all ? window.Livewire.all().find(c => c.name === 'transaction-modal') : null;
            if (comp && comp.$wire) {
                comp.$wire.open(type, houseId, unitId);
                return;
            }
            if (window.Livewire) {
                window.Livewire.dispatch('openTransactionModal', { type: type, houseId: houseId, unitId: unitId });
            } else {
                document.addEventListener('livewire:init', () => {
                    const c = window.Livewire?.all ? window.Livewire.all().find(x => x.name === 'transaction-modal') : null;
                    if (c && c.$wire) {
                        c.$wire.open(type, houseId, unitId);
                    } else {
                        window.Livewire.dispatch('openTransactionModal', { type: type, houseId: houseId, unitId: unitId });
                    }
                }, { once: true });
            }
        }
        window.openTransactionModal = openTransactionModal;

        function openEditPaymentModal(paymentId) {
            const comp = window.Livewire?.all ? window.Livewire.all().find(c => c.name === 'edit-payment-modal') : null;
            if (comp && comp.$wire) {
                comp.$wire.open(paymentId);
                return;
            }
            if (window.Livewire) {
                window.Livewire.dispatch('openEditPaymentModal', { paymentId: paymentId });
            } else {
                document.addEventListener('livewire:init', () => {
                    const c = window.Livewire?.all ? window.Livewire.all().find(x => x.name === 'edit-payment-modal') : null;
                    if (c && c.$wire) {
                        c.$wire.open(paymentId);
                    } else {
                        window.Livewire.dispatch('openEditPaymentModal', { paymentId: paymentId });
                    }
                }, { once: true });
            }
        }
        window.openEditPaymentModal = openEditPaymentModal;

        function toggleDarkMode() {
            const isDark = document.documentElement.classList.toggle('dark');
            document.cookie = "theme=" + (isDark ? 'dark' : 'light') + "; path=/; max-age=31536000"; // 1 year
        }
    </script>
</body>
</html>
