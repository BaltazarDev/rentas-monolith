@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-8 animate-fade-in pb-12">
    <!-- Header -->
    <div>
        <h1 class="text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-50">Carga Masiva de Datos</h1>
        <p class="text-sm text-slate-550 dark:text-slate-400 mt-1">Registra propiedades, departamentos e inquilinos simultáneamente mediante un archivo Excel/CSV.</p>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Upload Form and Template Card -->
        <div class="md:col-span-2 space-y-6">
            <!-- Action Card -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-805 dark:text-slate-200">1. Descarga la Plantilla</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Utiliza nuestro formato predefinido para organizar tus datos.</p>
                    </div>
                    <a href="{{ route('import.template') }}" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-indigo-650 bg-indigo-50 hover:bg-indigo-100 dark:text-indigo-400 dark:bg-indigo-950/40 dark:hover:bg-indigo-900/50 font-semibold text-xs transition duration-150">
                        📥 Descargar Plantilla Excel
                    </a>
                </div>

                <hr class="border-slate-100 dark:border-slate-700">

                <form action="{{ route('import.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <h3 class="text-lg font-bold text-slate-805 dark:text-slate-200">2. Sube tu Archivo</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Arrastra o selecciona el archivo CSV guardado desde Excel.</p>
                    </div>

                    <!-- Drag & Drop Zone -->
                    <div class="relative flex flex-col items-center justify-center border-2 border-dashed border-slate-250 dark:border-slate-700 rounded-2xl p-8 bg-slate-50/50 dark:bg-slate-900/30 hover:bg-slate-100/50 dark:hover:bg-slate-700/10 hover:border-indigo-400 transition group">
                        <input type="file" name="csv_file" id="csv_file" accept=".csv,text/csv,text/plain" required class="absolute inset-0 opacity-0 cursor-pointer">
                        <div class="text-center space-y-2">
                            <span class="text-3xl block group-hover:scale-110 transition duration-150">📊</span>
                            <span class="text-sm font-bold text-slate-700 dark:text-slate-300 block" id="file-name-text">
                                Arrastra tu archivo aquí o haz clic para buscar
                            </span>
                            <span class="text-xs text-slate-450 dark:text-slate-500 block">Soporta formatos .csv (delimitados por comas o punto y coma)</span>
                        </div>
                    </div>
                    @error('csv_file') <span class="text-xs text-rose-500 block mt-1">{{ $message }}</span> @enderror

                    <button type="submit" class="w-full py-3 px-4 rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] font-bold text-sm shadow-md transition duration-150">
                        Procesar e Importar Datos
                    </button>
                </form>
            </div>

            @if(isset($logs) && count($logs) > 0)
                <!-- Results Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-6">
                    <h3 class="text-lg font-bold text-slate-805 dark:text-slate-200">Resultados de la Importación</h3>
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100/50 dark:border-emerald-900/30 rounded-2xl text-center">
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-450 uppercase tracking-wider block">Casas</span>
                            <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 block mt-0.5">+{{ $totals['houses_created'] }}</span>
                        </div>
                        <div class="p-4 bg-indigo-50/50 dark:bg-indigo-950/20 border border-indigo-100/50 dark:border-indigo-900/30 rounded-2xl text-center">
                            <span class="text-[10px] font-bold text-indigo-650 dark:text-indigo-400 uppercase tracking-wider block">Unidades</span>
                            <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 block mt-0.5">+{{ $totals['units_created'] }}</span>
                        </div>
                        <div class="p-4 bg-teal-50/50 dark:bg-teal-950/20 border border-teal-100/50 dark:border-teal-900/30 rounded-2xl text-center">
                            <span class="text-[10px] font-bold text-teal-600 dark:text-teal-450 uppercase tracking-wider block">Inquilinos</span>
                            <span class="text-xl font-extrabold text-slate-800 dark:text-slate-200 block mt-0.5">+{{ $totals['tenants_created'] }}</span>
                        </div>
                        <div class="p-4 bg-rose-50/50 dark:bg-rose-950/20 border border-rose-100/50 dark:border-rose-900/30 rounded-2xl text-center">
                            <span class="text-[10px] font-bold text-rose-600 dark:text-rose-450 uppercase tracking-wider block">Errores</span>
                            <span class="text-xl font-extrabold text-rose-600 dark:text-rose-400 block mt-0.5">{{ $totals['errors_count'] }}</span>
                        </div>
                    </div>

                    <!-- Detailed logs -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Reporte por Fila</h4>
                        <div class="max-h-80 overflow-y-auto space-y-2 pr-2">
                            @foreach($logs as $log)
                                <div class="flex items-start justify-between gap-4 p-3 bg-slate-50 dark:bg-slate-900/30 border border-slate-100 dark:border-slate-800 rounded-xl text-xs font-medium">
                                    <div class="flex gap-2">
                                        <span class="text-slate-450 shrink-0">Fila {{ $log['row'] }}:</span>
                                        <span class="text-slate-700 dark:text-slate-300">{{ $log['message'] }}</span>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold shrink-0 {{ $log['status'] === 'success' ? 'text-emerald-650 bg-emerald-50 dark:text-emerald-450 dark:bg-emerald-950/20' : ($log['status'] === 'warning' ? 'text-amber-600 bg-amber-50 dark:text-amber-450 dark:bg-amber-950/20' : 'text-rose-600 bg-rose-50 dark:text-rose-400 dark:bg-rose-950/20') }}">
                                        {{ $log['status'] === 'success' ? 'Éxito' : ($log['status'] === 'warning' ? 'Advertencia' : 'Error') }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Instructions Panel -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700 space-y-4">
                <h3 class="text-sm font-bold text-slate-450 dark:text-slate-400 uppercase tracking-wider">Instrucciones de la Plantilla</h3>
                
                <div class="space-y-4 text-xs font-medium text-slate-600 dark:text-slate-400 leading-relaxed">
                    <p>El sistema analiza y vincula automáticamente los datos siguiendo estas reglas:</p>
                    
                    <ul class="list-disc pl-4 space-y-2 text-slate-700 dark:text-slate-350">
                        <li><strong class="text-indigo-600 dark:text-indigo-400">Autodetección de Casa:</strong> Si el nombre de la propiedad existe, se asocia la unidad a ella. Si no existe, se crea una propiedad nueva con esa dirección y descripción.</li>
                        <li><strong class="text-indigo-600 dark:text-indigo-400">Creación de Unidad:</strong> Si se especifica el nombre de unidad y renta, se crea en esa propiedad (si no existe). El tipo de unidad puede ser `apartment`, `commercial` o `house`.</li>
                        <li><strong class="text-indigo-600 dark:text-indigo-400">Asignación de Inquilino:</strong> Si se incluye un inquilino y la unidad está libre, se registra y vincula al departamento, cambiando su estado a ocupado.</li>
                        <li><strong class="text-amber-600 dark:text-amber-400">Evitar Duplicados:</strong> Si la unidad ya tiene un inquilino activo, se omite el registro del inquilino del CSV y se marca como advertencia.</li>
                    </ul>

                    <hr class="border-slate-100 dark:border-slate-700">

                    <div class="space-y-2">
                        <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">💡 Tip para Excel (Español):</h4>
                        <p class="text-[11px]">Cuando completes los datos en Microsoft Excel, ve a **Archivo -> Guardar como** y selecciona el formato **CSV (delimitado por comas) (*.csv)**. Nuestro importador detectará de forma automática si tu Excel guardó con coma (`,`) o punto y coma (`;`).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('csv_file');
        const fileNameText = document.getElementById('file-name-text');

        fileInput.addEventListener('change', function (e) {
            if (e.target.files && e.target.files.length > 0) {
                fileNameText.innerHTML = `✅ Archivo seleccionado:<br><span class="text-indigo-600 dark:text-indigo-400 font-semibold break-all text-xs">${e.target.files[0].name}</span>`;
            } else {
                fileNameText.innerText = 'Arrastra tu archivo aquí o haz clic para buscar';
            }
        });
    });
</script>
@endsection
