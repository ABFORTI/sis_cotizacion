@props(['cotizacion' => null])

@php
$aux = optional($cotizacion?->archivosAdjuntos);

$path = oldValue('path', $aux);

@endphp
<fieldset class="upload-section">
    <legend>Carga de Archivos</legend>

    <div class="form-group full-width">
        <label for="archivos" class="block mb-2 font-semibold text-gray-700">
            Cargar archivos locales (planos, especificaciones, etc.):
        </label>

        <!-- Área drag & drop -->
        <div id="drop_zone" class="drop-zone">
            <p class="drop-text">
                <span class="highlight">haz clic para seleccionar archivos</span>
            </p>
            <input
                type="file"
                id="archivos"
                name="archivos[]"
                multiple
                class="hidden">
        </div>

        {{-- Archivos ya cargados --}}
        @if($cotizacion && $cotizacion->archivosAdjuntos->isNotEmpty())
        <div class="mt-4">
            <p class="font-semibold mb-2">Archivos cargados previamente:</p>
            <div class="preview-grid" id="archivos_cargados">
                @foreach($cotizacion->archivosAdjuntos as $archivo)
                <div class="file-card" data-archivo="{{ $archivo->path }}">
                    @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $archivo->path))
                    <img src="{{ asset('storage/' . $archivo->path) }}" class="file-thumb mb-2" alt="Miniatura">
                    @else
                    <span class="block mb-2">{{ basename($archivo->path) }}</span>
                    @endif

                    <a href="{{ asset('storage/' . $archivo->path) }}" class="btn btn-download" download>
                        Descargar
                    </a>

                    <button type="button"
                        class="btn btn-delete"
                        onclick="eliminarArchivo('{{ addslashes($archivo->path) }}', '{{ $cotizacion->id }}', this)">
                        Quitar
                    </button>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        {{-- Previews de nuevos archivos --}}
        <div id="archivos_preview" class="mt-4 preview-grid"></div>
    </div>
</fieldset>