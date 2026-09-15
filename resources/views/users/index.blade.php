@extends('layouts.app')

@section('content')
<div class="space-y-8 animate-fade-in pb-12">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-base font-bold">
                    👥
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Usuarios y Accesos</h1>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestión de cuentas de administradores, asignación de roles y auditoría de accesos.</p>
        </div>

        @if($tab === 'users')
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-md font-semibold text-sm transition self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                Nuevo Usuario
            </a>
        @endif
    </div>

    <!-- Navigation Tabs -->
    <div class="border-b border-slate-200 dark:border-slate-700 flex items-center gap-6">
        <a href="{{ route('users.index', ['tab' => 'users']) }}" class="pb-3 text-sm font-bold flex items-center gap-2 border-b-2 transition {{ $tab === 'users' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            <span>👥</span> Usuarios del Sistema
            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $tab === 'users' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800' }}">
                {{ count($users) }}
            </span>
        </a>

        <a href="{{ route('users.index', ['tab' => 'logs']) }}" class="pb-3 text-sm font-bold flex items-center gap-2 border-b-2 transition {{ $tab === 'logs' ? 'border-indigo-600 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-slate-300' }}">
            <span>🛡️</span> Historial de Accesos (Logs)
            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $tab === 'logs' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800' }}">
                {{ $logs->total() }}
            </span>
        </a>
    </div>

    @if($tab === 'users')
        <!-- Tab 1: Users Table -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-slate-450 dark:text-slate-500 text-[10px] font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4">Correo Electrónico</th>
                            <th class="px-6 py-4">Rol Asignado</th>
                            <th class="px-6 py-4">Fecha Registro</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80">
                        @foreach($users as $user)
                            <tr class="text-slate-800 dark:text-slate-200 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full {{ $user->isSuperAdmin() ? 'bg-amber-500/20 text-amber-600 dark:text-amber-400' : 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400' }} flex items-center justify-center font-extrabold text-sm shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-sm font-bold block text-slate-850 dark:text-slate-100">{{ $user->name }}</span>
                                                @if(auth()->id() === $user->id)
                                                    <span class="text-[10px] font-bold px-1.5 py-0.2 rounded bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">Tú</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] text-slate-400">{{ $user->access_logs_count }} inicios de sesión registrados</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->isSuperAdmin())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-amber-50 dark:bg-amber-950/30 text-amber-700 dark:text-amber-400 border border-amber-200/60 dark:border-amber-900/50">
                                            👑 Super Administrador
                                        </span>
                                    @elseif($user->role === 'custom')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/30 text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-900/40">
                                            ⚙️ Personalizado ({{ count($user->getEffectivePermissions()) }} perms)
                                        </span>
                                    @elseif($user->isOperator())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/40">
                                            📋 Operador
                                            @if($user->hasCustomPermissions())
                                                <span class="text-[10px] font-semibold opacity-75">({{ count($user->getEffectivePermissions()) }} perms)</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-900/40">
                                            🛡️ Administrador
                                            @if($user->hasCustomPermissions())
                                                <span class="text-[10px] font-semibold opacity-75">({{ count($user->getEffectivePermissions()) }} perms)</span>
                                            @endif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-500 dark:text-slate-400">
                                    📅 {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-950/40 rounded-lg transition" title="Modificar usuario o contraseña">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            Editar
                                        </a>

                                        @if(auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar el usuario \'{{ $user->name }}\'? Esta acción no se puede deshacer.')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition" title="Eliminar usuario">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Tab 2: Access Logs Table -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-800/50 text-slate-450 dark:text-slate-500 text-[10px] font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                            <th class="px-6 py-4">Fecha y Hora</th>
                            <th class="px-6 py-4">Usuario / Correo Intentado</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Dirección IP</th>
                            <th class="px-6 py-4">Dispositivo / Navegador</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/80">
                        @forelse($logs as $log)
                            <tr class="text-slate-800 dark:text-slate-200 hover:bg-slate-50/50 dark:hover:bg-slate-700/10 transition">
                                <td class="px-6 py-4 text-xs font-semibold">
                                    📅 {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    <span class="text-[10px] text-slate-400 block font-normal">{{ $log->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold block">{{ $log->user ? $log->user->name : 'Usuario no existente' }}</span>
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400">{{ $log->email }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($log->status === 'success')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-400">
                                            ✓ Inicio Exitoso
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/30 text-rose-700 dark:text-rose-400">
                                            ✗ Fallido (Contraseña errónea)
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-slate-600 dark:text-slate-300">
                                    {{ $log->ip_address ?: '127.0.0.1' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 block max-w-xs truncate" title="{{ $log->user_agent }}">
                                        {{ $log->user_agent ?: 'Navegador desconocido' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    No hay registros de accesos todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50/30 dark:bg-slate-800/20">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
