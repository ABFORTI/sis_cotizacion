import "./bootstrap";

document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('archivos');
    const preview = document.getElementById('archivos_preview');

    if (input && preview) {
        input.addEventListener('change', function() {
            preview.innerHTML = '';
            const files = Array.from(input.files);

            files.forEach((file, idx) => {
                const card = document.createElement('div');
                card.className = 'file-card';

                // Miniatura si es imagen
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = 'file-thumb mb-2';
                    img.alt = 'Miniatura';
                    img.src = URL.createObjectURL(file);
                    card.appendChild(img);
                } else {
                    const span = document.createElement('span');
                    span.className = 'block mb-2';
                    span.textContent = file.name;
                    card.appendChild(span);
                }

                // Botón descargar (descarga el archivo local)
                const downloadBtn = document.createElement('a');
                downloadBtn.className = 'btn btn-download';
                downloadBtn.textContent = 'Descargar';
                downloadBtn.href = URL.createObjectURL(file);
                downloadBtn.download = file.name;
                card.appendChild(downloadBtn);

                // Botón eliminar
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.className = 'btn btn-delete';
                deleteBtn.textContent = 'Quitar';
                deleteBtn.onclick = function() {
                    files.splice(idx, 1);
                    // Actualiza el input y la vista
                    const dt = new DataTransfer();
                    files.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change'));
                };
                card.appendChild(deleteBtn);

                preview.appendChild(card);
            });
        });
    }

    // Toggle functionality for checkboxes
    const toggles = document.querySelectorAll('[data-toggle]');
    toggles.forEach(toggle => {
        const target = document.getElementById(toggle.dataset.toggle);
        if (!target) return;

        // Helper to set required on elements inside target marked with data-required="true"
        const setRequiredOnChildren = (enabled) => {
            const reqEls = target.querySelectorAll('[data-required="true"]');
            reqEls.forEach(el => {
                if (el instanceof HTMLInputElement || el instanceof HTMLTextAreaElement || el instanceof HTMLSelectElement) {
                    el.required = enabled;
                }
            });
        };

        if (toggle.tagName === 'SELECT') {
            // For select elements, show when value matches trigger (default '1') or matches data-toggle-value
            const triggerValue = toggle.dataset.toggleValue ?? '1';
            const updateSelect = (value) => {
                const shouldShow = String(value) === String(triggerValue);
                target.classList.toggle('is-hidden', !shouldShow);
                setRequiredOnChildren(shouldShow);
            };
            toggle.addEventListener('change', function() { updateSelect(this.value); });
            // initial state
            updateSelect(toggle.value);
        } else {
            // Default behavior for checkboxes (or other inputs with checked state)
            const update = (checked) => {
                target.classList.toggle('is-hidden', !checked);
                setRequiredOnChildren(checked);
            };
            toggle.addEventListener('change', function() { update(this.checked); });
            // initial state
            update(!!toggle.checked);
        }
    });
});

// Dropdown menu with auto-hide after 3 seconds
window.dropdownTimeout = null;

    window.toggleDropdown = function () {
        const menu = document.getElementById('dropdown-menu');

        // Verificar que el elemento existe antes de manipularlo
        if (!menu) {
            console.warn('Elemento dropdown-menu no encontrado');
            return;
        }

        // Mostrar el menú
        menu.classList.remove('hidden');

        // Cancelar cierre previo
        clearTimeout(window.dropdownTimeout);

        // Cerrar después de 3 segundos
        window.dropdownTimeout = setTimeout(() => {
            if (menu) {
                menu.classList.add('hidden');
            }
        }, 3000);
    };

    // Cerrar si se hace clic fuera
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('user-dropdown');
        const menu = document.getElementById('dropdown-menu');

        // Verificar que los elementos existan antes de usarlos
        if (dropdown && menu && !dropdown.contains(event.target)) {
            menu.classList.add('hidden');
            clearTimeout(window.dropdownTimeout);
        }
    });


// AJAX validation for unique project number

window.eliminarArchivoResumen = function (archivoId, btn) {
    if (!confirm('¿Seguro que deseas eliminar este archivo?')) return;

    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    fetch(`/resumen/archivo/${archivoId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            btn.closest('.file-card').remove();
        } else {
            alert('No se pudo eliminar el archivo');
        }
    })
    .catch(() => alert('Error al eliminar archivo'));
};




