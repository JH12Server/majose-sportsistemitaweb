@extends('layouts.app')

@section('title', 'Editor de Página Web')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="h3 mb-0 fw-bold" style="color: #e53935">
                        <i class="bi bi-globe"></i> Editor de Página Web
                    </h1>
                    <p class="text-muted mb-0">Edita el contenido de tu página web MajoseSport</p>
                </div>
                <div>
                    <a href="{{ route('pagina-web.preview') }}" target="_blank" class="btn btn-primary" style="background-color: #007bff;">
                        <i class="bi bi-eye"></i> Vista Previa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Panel de archivos -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 2px solid #e53935;">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-file-text" style="color: #e53935;"></i> Archivos
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div id="filesList" class="list-group list-group-flush">
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-hourglass-split"></i> Cargando archivos...
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Editor -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 2px solid #e53935;">
                    <div class="d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0">
                            <i class="bi bi-pencil" style="color: #e53935;"></i> <span id="currentFile">Selecciona un archivo</span>
                        </h6>
                        <button id="saveBtn" class="btn btn-sm btn-danger" style="background-color: #e53935; display: none;">
                            <i class="bi bi-floppy"></i> Guardar
                        </button>
                    </div>
                </div>
                <div class="card-body p-0" style="height: 600px; overflow: hidden;">
                    <textarea id="editor" class="form-control" style="height: 100%; border: none; border-radius: 0; font-family: 'Courier New', monospace; font-size: 0.9rem;" placeholder="Selecciona un archivo para editarlo"></textarea>
                </div>
                <div class="card-footer bg-light border-top">
                    <div id="statusMessage" class="text-muted small"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        cursor: pointer;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: #e53935;
    }
    
    .list-group-item.active {
        background-color: rgba(229, 57, 53, 0.1);
        border-left-color: #e53935;
        color: #e53935;
        font-weight: bold;
    }

    #editor {
        padding: 1rem;
        resize: none;
    }

    .badge-html { background-color: #e34c26; }
    .badge-css { background-color: #264de4; }
    .badge-js { background-color: #f7df1e; color: #000; }
</style>

@push('scripts')
<script>
    let files = [];
    let currentFile = null;
    let hasUnsavedChanges = false;
    const editor = document.getElementById('editor');
    const saveBtn = document.getElementById('saveBtn');
    const filesList = document.getElementById('filesList');
    const currentFileSpan = document.getElementById('currentFile');
    const statusMessage = document.getElementById('statusMessage');

    // Cargar lista de archivos
    function loadFiles() {
        fetch('{{ route("dashboard") }}')
            .then(response => response.json())
            .catch(() => {
                // Si falla, usar archivos de prueba
                files = {!! json_encode($files) !!};
                renderFiles();
            });

        files = {!! json_encode($files) !!};
        renderFiles();
    }

    function renderFiles() {
        if (files.length === 0) {
            filesList.innerHTML = '<div class="text-center py-4 text-muted"><p>No hay archivos para editar</p></div>';
            return;
        }

        filesList.innerHTML = files.map(file => `
            <div class="list-group-item" data-file="${file.name}" onclick="selectFile('${file.name}')">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <strong>${file.name}</strong><br>
                        <small class="text-muted">${file.type.toUpperCase()}</small>
                    </div>
                    <span class="badge badge-${file.type}">${file.type}</span>
                </div>
            </div>
        `).join('');
    }

    function selectFile(fileName) {
        if (hasUnsavedChanges) {
            if (!confirm('Tienes cambios sin guardar. ¿Deseas continuar?')) {
                return;
            }
        }

        // Marcar como activo
        document.querySelectorAll('.list-group-item').forEach(item => {
            item.classList.remove('active');
        });
        document.querySelector(`[data-file="${fileName}"]`).classList.add('active');

        currentFile = fileName;
        editor.value = 'Cargando...';
        saveBtn.style.display = 'none';
        statusMessage.textContent = 'Cargando archivo...';

        fetch(`{{ route('pagina-web.get-file') }}?file=${encodeURIComponent(fileName)}`)
            .then(response => {
                if (!response.ok) throw new Error('Error al cargar el archivo');
                return response.json();
            })
            .then(data => {
                editor.value = data.content;
                currentFileSpan.textContent = data.file;
                saveBtn.style.display = 'inline-block';
                statusMessage.textContent = 'Archivo cargado correctamente';
                hasUnsavedChanges = false;
            })
            .catch(error => {
                editor.value = '';
                statusMessage.textContent = '❌ Error al cargar el archivo: ' + error.message;
                saveBtn.style.display = 'none';
            });
    }

    editor.addEventListener('input', () => {
        hasUnsavedChanges = true;
        statusMessage.textContent = '⚠️ Hay cambios sin guardar';
    });

    saveBtn.addEventListener('click', () => {
        if (!currentFile) return;

        saveBtn.disabled = true;
        statusMessage.textContent = 'Guardando...';

        fetch('{{ route("pagina-web.save-file") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                file: currentFile,
                content: editor.value
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Error al guardar');
            return response.json();
        })
        .then(data => {
            statusMessage.textContent = '✅ ' + data.message;
            hasUnsavedChanges = false;
            saveBtn.disabled = false;
        })
        .catch(error => {
            statusMessage.textContent = '❌ Error: ' + error.message;
            saveBtn.disabled = false;
        });
    });

    // Cargar archivos al iniciar
    loadFiles();

    // Advertir antes de cerrar si hay cambios sin guardar
    window.addEventListener('beforeunload', (e) => {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endpush
@endsection
