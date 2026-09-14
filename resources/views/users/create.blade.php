@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto space-y-8 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Registrar Usuario</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Crea una nueva cuenta de acceso y asígnale su nivel de permisos.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
            <!-- Full Name -->
            <div>
                <label for="name" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nombre Completo</label>
                <input type="text" name="name" id="name" required placeholder="Ej: Roberto Sánchez" value="{{ old('name') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Correo Electrónico</label>
                <input type="email" name="email" id="email" required placeholder="correo@ejemplo.com" value="{{ old('email') }}" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Role Selection -->
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Rol de Acceso</label>
                <select name="role" id="role" required class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        🛡️ Administrador Estándar (Registro de cobros, inquilinos, gastos y propiedades)
                    </option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>
                        👑 Super Administrador (Control total: editar historial de pagos, usuarios, importación y exportación)
                    </option>
                </select>
                @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Contraseña (Mínimo 6 caracteres)</label>
                <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Registrar Usuario
        </button>
    </form>
</div>
@endsection
