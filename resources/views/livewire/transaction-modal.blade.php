<div>
    @if($isOpen)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
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
                        <select wire:model="unitId" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">Ninguna</option>
                            @foreach($units as $u)
                                @if(!$houseId || $u['house_id'] == $houseId)
                                    <option value="{{ $u['id'] }}">{{ $u['name'] }} ({{ $u['house']['name'] ?? 'Casa' }})</option>
                                @endif
                            @endforeach
                        </select>
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
