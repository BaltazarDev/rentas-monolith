<div>
    @if($isOpen)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 z-[70] overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
            <!-- Modal Box -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all scale-100 flex flex-col border border-slate-100 dark:border-slate-700">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-150 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-800">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 flex items-center gap-2">
                        @if($txType === 'payment')
                            <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Registrar Pago (Ingreso)
                        @else
                            <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Registrar Gasto (Egreso)
                        @endif
                    </h3>
                    <button wire:click="close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-350 focus:outline-none rounded-full p-1 hover:bg-slate-100 dark:hover:bg-slate-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 space-y-4 overflow-y-auto max-h-[75vh]">
                    <!-- Type Selector -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipo de Transacción</label>
                        <select wire:model.live="txType" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="payment">Ingreso (Pago de Inquilino)</option>
                            <option value="expense">Egreso (Gasto/Costo)</option>
                        </select>
                    </div>

                    @if($txType === 'expense')
                        <!-- Property selection (optional for expense) -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Propiedad</label>
                            <select wire:model.live="houseId" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Ninguna (Gasto General)</option>
                                @foreach($houses as $h)
                                    <option value="{{ $h['id'] }}">{{ $h['name'] }}</option>
                                @endforeach
                            </select>
                            @error('houseId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Unit selection -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Unidad / Departamento {{ $txType === 'payment' ? '(Requerido)' : '(Opcional)' }}
                        </label>

                        @if($this->selectedUnit)
                            <!-- Selected unit card -->
                            <div class="p-3 bg-indigo-50/70 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 rounded-2xl flex items-center justify-between gap-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                        🚪
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-slate-100 truncate">
                                            {{ $this->selectedUnit['name'] }}
                                        </h4>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                            📍 {{ $this->selectedUnit['house']['name'] ?? 'Casa' }}
                                            @if(!empty($this->selectedUnit['tenant']))
                                                • <span class="text-emerald-600 dark:text-emerald-400 font-semibold">👤 {{ $this->selectedUnit['tenant']['full_name'] }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <button 
                                    type="button" 
                                    wire:click="clearUnit" 
                                    class="px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-white dark:bg-slate-800 border border-indigo-200 dark:border-indigo-700/60 hover:bg-indigo-50 dark:hover:bg-slate-700 transition shrink-0 shadow-sm"
                                >
                                    Cambiar
                                </button>
                            </div>
                        @else
                            <!-- Searchable Selector -->
                            <div class="space-y-2">
                                <!-- Search input -->
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </span>
                                    <input 
                                        type="text" 
                                        wire:model.live.debounce.150ms="unitSearch" 
                                        placeholder="Escribe depto, casa o inquilino..." 
                                        class="w-full rounded-xl border border-slate-300 dark:border-slate-600 pl-10 pr-9 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-xs"
                                    >
                                    @if($unitSearch)
                                        <button 
                                            type="button" 
                                            wire:click="$set('unitSearch', '')" 
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 text-xs font-bold"
                                        >
                                            ✕
                                        </button>
                                    @endif
                                </div>

                                <!-- List of units (touch friendly for mobile) -->
                                <div class="max-h-48 overflow-y-auto rounded-xl border border-slate-200 dark:border-slate-600 divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-750 shadow-inner">
                                    @if($txType === 'expense')
                                        <button 
                                            type="button" 
                                            wire:click="selectUnit('')" 
                                            class="w-full text-left px-3.5 py-2 text-xs font-semibold text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-700 transition"
                                        >
                                            ✕ Ninguna (Gasto General)
                                        </button>
                                    @endif

                                    @forelse($this->filteredUnits as $u)
                                        <button 
                                            type="button" 
                                            wire:click="selectUnit({{ $u['id'] }})" 
                                            class="w-full text-left px-3.5 py-2.5 flex items-center justify-between hover:bg-indigo-50 dark:hover:bg-indigo-950/40 active:bg-indigo-100 transition group"
                                        >
                                            <div class="min-w-0 pr-2">
                                                <span class="text-xs font-bold text-slate-850 dark:text-slate-100 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 block truncate">
                                                    🚪 {{ $u['name'] }}
                                                    <span class="text-[11px] font-normal text-slate-400">({{ $u['house']['name'] ?? 'Casa' }})</span>
                                                </span>
                                                @if(!empty($u['tenant']))
                                                    <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-medium block truncate mt-0.5">
                                                        👤 {{ $u['tenant']['full_name'] }}
                                                    </span>
                                                @else
                                                    <span class="text-[10px] text-slate-400 italic block">
                                                        Disponible
                                                    </span>
                                                @endif
                                            </div>
                                            <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 shrink-0 opacity-80 group-hover:opacity-100">
                                                Elegir →
                                            </span>
                                        </button>
                                    @empty
                                        <div class="p-4 text-center text-xs text-slate-400">
                                            No se encontraron unidades para "{{ $unitSearch }}"
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        @error('unitId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    @if($txType === 'payment')
                        <!-- Payment Concept -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Concepto de Pago</label>
                            <select wire:model="paymentType" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="rent">Renta Mensual</option>
                                <option value="utility">Servicios (Luz, Agua, etc.)</option>
                            </select>
                            @error('paymentType') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <!-- Expense Concept -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Concepto del Gasto</label>
                            <input type="text" wire:model="expenseType" placeholder="Ej: Reparación, Pintura, Luz General" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('expenseType') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <!-- Amount -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Monto ($ MXN)</label>
                        <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('amount') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha</label>
                        <input type="date" wire:model="date" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        @error('date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Notas/Detalles</label>
                        <textarea wire:model="notes" rows="3" placeholder="Detalles adicionales..." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        @error('notes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Receipt File Upload -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Comprobante (Archivo)</label>
                        <div class="relative flex items-center justify-center border-2 border-dashed border-slate-350 dark:border-slate-650 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-100/50 dark:hover:bg-slate-700/30 transition">
                            <input type="file" wire:model="receipt" id="receipt" accept="image/*,application/pdf" class="absolute inset-0 opacity-0 cursor-pointer">
                            <div class="text-center space-y-1">
                                <span class="text-xl block">📄</span>
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                                    @if($receipt)
                                        ✅ Archivo listo: {{ $receipt->getClientOriginalName() }}
                                    @else
                                        Subir imagen o PDF (máx. 5MB)
                                    @endif
                                </span>
                            </div>
                        </div>
                        @error('receipt') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="receipt" class="text-xs text-indigo-650 dark:text-indigo-400 mt-1 block">
                            ⏳ Subiendo archivo...
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-150 dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-800/50">
                    <button wire:click="close" class="px-4 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <button wire:click="save" class="px-5 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-semibold text-sm shadow-md transition">
                        Guardar Transacción
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
