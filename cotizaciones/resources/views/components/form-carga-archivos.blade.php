@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->archivosAdjuntos);

$path = oldValue('path', $aux);

@endphp
<fieldset class="upload-section">
    <legend>Carga de Archivos</legend>
        <div class="form-group full-width">
            <label for="archivos" class="block mb-3 text-sm font-semibold text-gray-800">
                Cargar archivos locales
                <span class="text-gray-500 font-normal text-xs ml-1">(planos, especificaciones, etc.)</span>
            </label>
            <div id="drop_zone" class="relative border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-300 hover:border-blue-400 hover:bg-blue-50/50 cursor-pointer group">
                <div class="flex flex-col items-center gap-3">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-gray-700 font-medium">
                            Arrastra tus archivos aquí o 
                            <span class="text-blue-600 font-semibold hover:text-blue-700">haz clic para seleccionar</span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            Formatos compatibles: PDF, JPG, PNG, GIF, DWG, DXF, ZIP &mdash; máx. 25 MB por archivo
                        </p>
                    </div>
                </div>
                <input
                    type="file"
                    id="archivos"
                    name="archivos[]"
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.gif,.dwg,.dxf,.zip"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
            </div>
            @if($cotizacion && $cotizacion->archivosAdjuntos->isNotEmpty())
                <div class="mt-6">
                    <p class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Archivos cargados previamente
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4" id="archivos_cargados">
            @foreach($cotizacion->archivosAdjuntos as $archivo)
                        <div class="relative group bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200" 
                            data-archivo="{{ $archivo->path }}">
                        <div class="aspect-square bg-gray-50 flex items-center justify-center p-3">
                                @php
                                    $ext = strtolower($archivo->tipo_archivo ?? pathinfo($archivo->path, PATHINFO_EXTENSION));
                                @endphp
                                @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ route('archivos.preview', $archivo->id) }}"
                                        class="w-full h-full object-cover rounded"
                                        alt="Miniatura">
                                @else
                                    <div class="text-center">
                                        @if($ext === 'pdf')
                                            <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM8.5 17v-1h7v1h-7zm0-3v-1h7v1h-7zm0-3v-1h4v1h-4z"/>
                                            </svg>
                                        @elseif(in_array($ext, ['dwg', 'dxf']))
                                            <svg class="w-12 h-12 text-yellow-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        @elseif($ext === 'zip')
                                            <svg class="w-12 h-12 text-purple-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                            </svg>
                                        @else
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        @endif
                                        <span class="text-xs text-gray-600 font-medium">{{ strtoupper($ext) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="p-2 bg-white border-t border-gray-100">
                                <p class="text-xs text-gray-700 truncate font-medium" title="{{ $archivo->nombre_original ?? basename($archivo->path) }}">
                                    {{ $archivo->nombre_original ?? basename($archivo->path) }}
                                </p>
                            </div>
                            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center gap-2">
                                <a href="{{ route('archivos.download', $archivo->id) }}" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors flex items-center gap-1" 
                                    download>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Descargar
                                </a>
                                <button type="button"
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-medium px-3 py-2 rounded-lg transition-colors flex items-center gap-1"
                                    onclick="eliminarArchivo('{{ addslashes($archivo->path) }}', '{{ $cotizacion->id }}', this)">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Quitar
                                </button>
                            </div>
                        </div>
            @endforeach
                    </div>
                </div>
            @endif
            <div id="archivos_preview" class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 empty:hidden"></div>
        </div>
</fieldset>