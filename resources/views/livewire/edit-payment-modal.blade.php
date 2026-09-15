<div>
    @if($isOpen)
        <!-- Modal Overlay -->
        <div class="fixed inset-0 z-[70] overflow-y-auto flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300">
            <!-- Modal Box -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all scale-100 flex flex-col border border-slate-100 dark:border-slate-700">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-slate-150 dark:border-slate-700 flex justify-between items-center bg-gradient-to-r from-indigo-50/50 to-slate-50 dark:from-slate-800 dark:to-slate-800/80">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold">
                            👑
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-850 dark:text-slate-100">
                                Corregir Pago
                            </h3>
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-600 dark:text-amber-400">
                                Exclusivo Super Admin
                            </span>
                        </div>
                    </div>
                    <button wire:click="close" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 focus:outline-none rounded-full p-1 hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Body -->
                <div class="p-6 space-y-4 overflow-y-auto max-h-[75vh]">
                    <!-- Unit / Tenant Selection (Persona) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Persona / Unidad Asignada
                        </label>
                        <select wire:model="unitId" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            <option value="">Selecciona la unidad e inquilino</option>
                            @foreach($units as $u)
                                <option value="{{ $u['id'] }}">
                                    {{ $u['label'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('unitId') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Amount and Concept in 2 cols -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Amount -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                Monto ($ MXN)
                            </label>
                            <input type="number" step="0.01" wire:model="amount" placeholder="0.00" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                            @error('amount') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Concept -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                                Concepto
                            </label>
                            <select wire:model="paymentType" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                                <option value="rent">Renta Mensual</option>
                                <option value="utility">Servicios / Utilidades</option>
                            </select>
                            @error('paymentType') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Fecha del Pago
                        </label>
                        <input type="date" wire:model="date" class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm">
                        @error('date') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                            Notas u Observaciones
                        </label>
                        <textarea wire:model="notes" rows="2" placeholder="Motivo de corrección o detalles del pago..." class="w-full rounded-xl border border-slate-300 dark:border-slate-600 px-4 py-2.5 bg-white dark:bg-slate-700 text-slate-800 dark:text-slate-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 text-sm"></textarea>
                        @error('notes') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Receipt Section -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            Comprobante de Pago
                        </label>

                        @if($currentReceiptUrl && !$deleteReceipt)
                            <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <a href="{{ $currentReceiptUrl }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline truncate max-w-[280px]">
                                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                    Ver comprobante actual
                                </a>
                                <button type="button" wire:click="removeReceipt" class="text-xs font-semibold text-rose-600 hover:text-rose-700 hover:underline">
                                    Eliminar
                                </button>
                            </div>
                        @elseif($deleteReceipt)
                            <div class="p-2.5 bg-amber-50 dark:bg-amber-950/30 text-amber-800 dark:text-amber-300 rounded-xl text-xs flex items-center justify-between">
                                <span>⚠️ El comprobante actual se eliminará al guardar.</span>
                                <button type="button" wire:click="$set('deleteReceipt', false)" class="text-xs font-bold underline">Deshacer</button>
                            </div>
                        @endif

                        <!-- Upload New Receipt -->
                        <div class="relative flex items-center justify-center border-2 border-dashed border-slate-300 dark:border-slate-650 rounded-xl p-4 bg-slate-50/50 dark:bg-slate-900/50 hover:bg-slate-100/50 dark:hover:bg-slate-700/30 transition">
                            <input type="file" wire:model="newReceipt" id="edit-receipt-input" accept="image/*,application/pdf" class="absolute inset-0 opacity-0 cursor-pointer">
                            <div class="text-center space-y-1">
                                <span class="text-xl block">📄</span>
                                <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">
                                    @if($newReceipt)
                                        ✅ Nuevo archivo: {{ $newReceipt->getClientOriginalName() }}
                                    @else
                                        {{ $currentReceiptUrl ? 'Reemplazar con nuevo archivo (imagen/PDF máx 5MB)' : 'Subir comprobante (imagen o PDF máx 5MB)' }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        @error('newReceipt') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        <div wire:loading wire:target="newReceipt" class="text-xs text-indigo-600 dark:text-indigo-400 mt-1 block">
                            ⏳ Subiendo nuevo archivo...
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 border-t border-slate-150 dark:border-slate-700 flex justify-end gap-3 bg-slate-50 dark:bg-slate-800/50">
                    <button wire:click="close" type="button" class="px-4 py-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 font-semibold text-sm transition">
                        Cancelar
                    </button>
                    <button wire:click="save" type="button" class="px-5 py-2 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 font-semibold text-sm shadow-md transition flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
