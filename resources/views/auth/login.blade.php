<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50 dark:bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Iniciar Sesión - Control de Rentas</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS & JS Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 bg-gradient-to-tr from-slate-100 via-slate-50 to-indigo-50/50 dark:from-slate-950 dark:via-slate-900 dark:to-indigo-950/20 text-slate-800 dark:text-slate-100">
    <div class="w-full max-w-md">
        <!-- Brand Logo / Header -->
        <div class="text-center mb-8 flex flex-col items-center">
            <div class="w-14 h-14 rounded-2xl bg-indigo-600 shadow-lg flex items-center justify-center text-white mb-4 shadow-indigo-500/20">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Control de Rentas</h2>
            <p class="text-sm text-slate-550 dark:text-slate-400 mt-2">Introduce tus credenciales para acceder</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-8 border border-slate-100 dark:border-slate-700/50">
            <form action="/login" method="POST" class="space-y-5">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                            placeholder="ejemplo@correo.com" 
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 pl-11 pr-4 py-3 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                        >
                    </div>
                    @error('email')
                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2">Contraseña</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required 
                            placeholder="••••••••" 
                            class="w-full rounded-2xl border border-slate-200 dark:border-slate-700 pl-11 pr-4 py-3 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"
                        >
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="rounded-md border-slate-300 dark:border-slate-700 text-indigo-600 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 h-4.5 w-4.5 transition cursor-pointer"
                    >
                    <label for="remember" class="ml-2.5 text-sm text-slate-550 dark:text-slate-400 select-none cursor-pointer">Recordar sesión</label>
                </div>

                <!-- Action Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 hover:shadow-xl transition-all duration-200"
                    >
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
