@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in pb-16">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('users.index') }}" class="p-2 text-slate-400 hover:text-slate-650 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
        </a>
        <div>
            <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Registrar Usuario</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Crea una nueva cuenta y configura sus permisos y accesos específicos.</p>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Basic Info Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-5">
            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Información Básica</h3>

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
                <label for="role" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Rol Base / Plantilla</label>
                <select name="role" id="role" required onchange="onRoleChange(this.value)" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    <option value="operator" {{ old('role', 'operator') === 'operator' ? 'selected' : '' }}>
                        📋 Operador (Propiedades, inquilinos activos y registrar cobros/gastos)
                    </option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                        🛡️ Administrador Estándar (Control operativo total de cobros, contratos y propiedades)
                    </option>
                    <option value="custom" {{ old('role') === 'custom' ? 'selected' : '' }}>
                        ⚙️ Personalizado (Selección manual de permisos específicos)
                    </option>
                    <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>
                        👑 Super Administrador (Acceso irrestricto a todo el sistema, usuarios y base de datos)
                    </option>
                </select>
                @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Password Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Contraseña (Mínimo 6)</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                    @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 px-4 py-2.5 bg-slate-50/50 dark:bg-slate-900/50 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                </div>
            </div>
        </div>

        <!-- Permissions Section -->
        <div id="permissions-container" class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-slate-700 pb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-850 dark:text-slate-100 flex items-center gap-2">
                        <span>🔐</span> Permisos y Accesos Granulares
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                        Marca o desmarca las funciones específicas que deseas conceder a este usuario.
                    </p>
                </div>

                <!-- Quick Presets -->
                <div class="flex items-center gap-1.5 flex-wrap">
                    <button type="button" onclick="applyPreset('operator')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-emerald-50 hover:text-emerald-700 transition">
                        📋 Operador
                    </button>
                    <button type="button" onclick="applyPreset('admin')" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-700 transition">
                        🛡️ Admin
                    </button>
                    <button type="button" onclick="toggleAllPermissions(true)" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                        ✓ Todos
                    </button>
                    <button type="button" onclick="toggleAllPermissions(false)" class="px-2.5 py-1 text-xs font-bold rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-200 transition">
                        ✕ Ninguno
                    </button>
                </div>
            </div>

            <!-- Super Admin Notice (hidden unless super admin selected) -->
            <div id="super-admin-notice" class="hidden p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-300 text-xs flex items-center gap-3">
                <span class="text-xl">👑</span>
                <div>
                    <strong class="font-bold block">Acceso Total de Super Administrador</strong>
                    <span>Este rol siempre tiene habilitadas todas las funciones y permisos del sistema sin restricciones.</span>
                </div>
            </div>

            <!-- Permissions Groups -->
            <div id="permissions-grid" class="space-y-6">
                @php
                    $oldPermissions = old('permissions', $roleDefaultPermissions['operator'] ?? []);
                @endphp

                @foreach($permissionsMap as $groupKey => $group)
                    <div class="space-y-2.5">
                        <h4 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                            {{ $group['label'] }}
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5">
                            @foreach($group['permissions'] as $permKey => $perm)
                                @php
                                    $isChecked = in_array($permKey, $oldPermissions);
                                @endphp
                                <label class="perm-card relative flex items-start gap-3 p-3 rounded-2xl border transition-all duration-150 cursor-pointer select-none bg-slate-50/60 dark:bg-slate-900/40 border-slate-200/80 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-slate-600">
                                    <div class="flex items-center h-5 mt-0.5">
                                        <input 
                                            type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permKey }}" 
                                            data-perm="{{ $permKey }}"
                                            {{ $isChecked ? 'checked' : '' }}
                                            class="perm-checkbox w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-600"
                                        >
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <span class="text-xs font-bold text-slate-800 dark:text-slate-100 block">
                                            {{ $perm['label'] }}
                                        </span>
                                        <span class="text-[11px] text-slate-400 dark:text-slate-400 leading-snug block mt-0.5">
                                            {{ $perm['description'] }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            @error('permissions') <span class="text-xs text-rose-500 block">{{ $message }}</span> @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-semibold text-sm shadow-md transition-all">
            Registrar Usuario y Guardar Permisos
        </button>
    </form>
</div>

<script>
    const rolePresets = {!! json_encode($roleDefaultPermissions) !!};
    const allKeys = {!! json_encode(\App\Models\User::getAllPermissionKeys()) !!};

    function onRoleChange(role) {
        const superNotice = document.getElementById('super-admin-notice');
        const grid = document.getElementById('permissions-grid');
        const checkboxes = document.querySelectorAll('.perm-checkbox');

        if (role === 'super_admin') {
            if (superNotice) superNotice.classList.remove('hidden');
            checkboxes.forEach(cb => {
                cb.checked = true;
                cb.disabled = true;
            });
        } else {
            if (superNotice) superNotice.classList.add('hidden');
            checkboxes.forEach(cb => {
                cb.disabled = false;
            });

            if (rolePresets[role]) {
                applyPreset(role);
            }
        }
    }

    function applyPreset(role) {
        const allowed = rolePresets[role] || [];
        const checkboxes = document.querySelectorAll('.perm-checkbox');
        checkboxes.forEach(cb => {
            const key = cb.getAttribute('data-perm');
            cb.checked = allowed.includes(key);
        });
    }

    function toggleAllPermissions(check) {
        const checkboxes = document.querySelectorAll('.perm-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = check;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const currentRole = document.getElementById('role').value;
        if (currentRole === 'super_admin') {
            onRoleChange('super_admin');
        }
    });
</script>
@endsection

