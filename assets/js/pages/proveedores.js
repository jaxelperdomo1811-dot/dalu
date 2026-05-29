/**
 * proveedores.js – Inicialización de intlTelInput para formularios de proveedores (usando CDN).
 */
document.addEventListener('DOMContentLoaded', () => {
    if (!window.intlTelInput) {
        console.error('[proveedores.js] intlTelInput no está cargado.');
        return;
    }

    const errorMap = [
        "Número inválido",
        "Código de país no válido",
        "Demasiado corto",
        "Demasiado largo",
        "Número inválido"
    ];

    // Mapa de instancias: input -> iti
    const itiInstances = new Map();

    /**
     * Inicializa intlTelInput en un input dado.
     */
    function initIti(input) {
        if (itiInstances.has(input)) return itiInstances.get(input);

        const iti = window.intlTelInput(input, {
            initialCountry: "ve",
            nationalMode: false,
            showSelectedDialCode: true,
            preferredCountries: ['ve', 'us', 'co'],
            hiddenInputs: {
                phone: "phone_full",
                country: "country_iso2"
            },
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@24.5.0/build/js/utils.js"
        });

        itiInstances.set(input, iti);

        const errorMsg = input.parentElement.querySelector('.error-msg');
        const validMsg = input.parentElement.querySelector('.valid-msg');

        const reset = () => {
            input.classList.remove('error');
            input.setCustomValidity('');
            if (errorMsg) { errorMsg.innerHTML = ''; errorMsg.style.display = 'none'; }
            if (validMsg) { validMsg.style.display = 'none'; }
        };

        const validate = () => {
            reset();
            if (!input.value.trim()) return;

            if (iti.isValidNumber()) {
                if (validMsg) validMsg.style.display = 'block';
            } else {
                input.classList.add('error');
                const code = iti.getValidationError();
                const msg = errorMap[code] ?? 'Número inválido';
                input.setCustomValidity(msg);
                if (errorMsg) { errorMsg.innerHTML = msg; errorMsg.style.display = 'block'; }
            }
        };

        input.addEventListener('blur', validate);
        input.addEventListener('change', reset);
        input.addEventListener('keyup', reset);

        return iti;
    }

    // Inicializar de forma inmediata todos los campos telefónicos de la página
    document.querySelectorAll('input.phone-input').forEach(input => {
        initIti(input);
    });

    /**
     * Interceptar el envío de CADA formulario para:
     *  1. Bloquear si el número es inválido.
     *  2. Reemplazar el valor del input con el número E.164 (+CCXXXXXXXXX).
     */
    document.addEventListener('submit', (event) => {
        const form = event.target;
        const phoneField = form.querySelector('input.phone-input');
        if (!phoneField) return;

        const iti = itiInstances.get(phoneField);
        if (!iti) return;

        // Limpiar validez previa
        phoneField.setCustomValidity('');

        if (phoneField.value.trim()) {
            if (!iti.isValidNumber()) {
                const code = iti.getValidationError();
                const msg = errorMap[code] ?? 'Número de teléfono inválido';

                phoneField.classList.add('error');
                phoneField.setCustomValidity(msg);

                const errorMsg = phoneField.parentElement.querySelector('.error-msg');
                if (errorMsg) { errorMsg.innerHTML = msg; errorMsg.style.display = 'block'; }

                const validMsg = phoneField.parentElement.querySelector('.valid-msg');
                if (validMsg) validMsg.style.display = 'none';

                event.preventDefault();
                phoneField.reportValidity();
                return;
            }

            // Reemplazar con formato E.164 (+58XXXXXXXXXX)
            phoneField.value = iti.getNumber();
        }
    }, true);

    // API Cedula integration
    const cedulaInput = document.getElementById('cedula');
    const tipoPersonaSelect = document.getElementById('tipo_persona');
    const nombreInput = document.getElementById('nombre');
    const apellidoInput = document.getElementById('apellido');
    const mensajeCedula = document.getElementById('mensaje-cedula');

    if (cedulaInput && tipoPersonaSelect) {
        cedulaInput.addEventListener('blur', async () => {
            const cedula = cedulaInput.value.trim();
            const tipo = tipoPersonaSelect.value;
            
            if (cedula.length >= 6 && tipo) {
                try {
                    mensajeCedula.style.color = 'blue';
                    mensajeCedula.innerText = 'Consultando documento...';
                    
                    const response = await fetch(`?c=proveedores&accion=consultarCedula&tipo_persona=${tipo}&cedula=${cedula}`);
                    const textData = await response.text();
                    
                    try {
                        const data = JSON.parse(textData);
                        if (data.data) {
                            const persona = data.data;
                            const modal = cedulaInput.closest('.modal') || document;
                            const nInput = modal.querySelector('input[name="nombre"]');
                            const aInput = modal.querySelector('input[name="apellido"]');
                            const rInput = modal.querySelector('input[name="rif"]');
                            
                            if (nInput) nInput.value = `${persona.primer_nombre || ''} ${persona.segundo_nombre || ''}`.trim();
                            if (aInput) aInput.value = `${persona.primer_apellido || ''} ${persona.segundo_apellido || ''}`.trim();
                            if (rInput && persona.rif) rInput.value = persona.rif;
                            
                            mensajeCedula.style.color = 'green';
                            mensajeCedula.innerText = 'Datos encontrados.';
                        } else {
                            mensajeCedula.style.color = 'red';
                            mensajeCedula.innerText = 'Documento no encontrado.';
                        }
                    } catch (e) {
                        console.error('Invalid JSON response', textData);
                        mensajeCedula.style.color = 'red';
                        mensajeCedula.innerText = 'Error al parsear la respuesta.';
                    }
                } catch (error) {
                    console.error('Error al consultar documento:', error);
                    mensajeCedula.style.color = 'red';
                    mensajeCedula.innerText = 'Error al conectar con la API.';
                }
            } else {
                mensajeCedula.innerText = '';
            }
        });
    }

    // Dynamic Rows Logic for Entradas
    const btnAddProducto = document.getElementById('btn_add_producto');
    const container = document.getElementById('productos_container');
    let currentVarianteSelect = null;

    if (btnAddProducto && container) {
        btnAddProducto.addEventListener('click', () => {
            const row = container.querySelector('.producto-row');
            if (row) {
                const newRow = row.cloneNode(true);
                // Reset values
                newRow.querySelectorAll('input, select').forEach(input => {
                    input.value = '';
                });
                
                // Reset variante select
                const varSelect = newRow.querySelector('.variante-select');
                if (varSelect) {
                    varSelect.innerHTML = '<option value="" disabled selected>Seleccione variante</option>';
                }

                container.appendChild(newRow);
            }
        });

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('remover-producto')) {
                const rows = container.querySelectorAll('.producto-row');
                if (rows.length > 1) {
                    e.target.closest('.producto-row').remove();
                } else {
                    alert('Debe haber al menos un producto.');
                }
            }
            
            if (e.target.classList.contains('btn-add-variante')) {
                const row = e.target.closest('.producto-row');
                const prodSelect = row.querySelector('.producto-select');
                const idProd = prodSelect.value;
                if (!idProd) {
                    alert('Debe seleccionar un producto primero.');
                    return;
                }
                
                const catName = prodSelect.getAttribute('data-categoria') || '';
                
                currentVarianteSelect = row.querySelector('.variante-select');
                document.getElementById('vr_id_producto').value = idProd;
                document.getElementById('formVarianteRapida').reset();
                
                const dynamicFieldsContainer = document.getElementById('dynamicVariantFields');
                if (dynamicFieldsContainer) {
                    dynamicFieldsContainer.innerHTML = getVariantExtrasHtml(catName);
                }
                
                const modalEl = document.getElementById('modalAgregarVarianteRapida');
                if (modalEl) {
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            }
        });
        
        container.addEventListener('change', async (e) => {
            if (e.target.classList.contains('producto-select')) {
                const row = e.target.closest('.producto-row');
                const varSelect = row.querySelector('.variante-select');
                const idProd = e.target.value;
                
                if (idProd && varSelect) {
                    varSelect.innerHTML = '<option value="">Cargando...</option>';
                    try {
                        const response = await fetch('?c=productos&accion=getProductoJson&id=' + idProd);
                        const data = await response.json();
                        
                        if (data && data.categoria_nombre) {
                            e.target.setAttribute('data-categoria', data.categoria_nombre);
                        } else {
                            e.target.removeAttribute('data-categoria');
                        }
                        
                        varSelect.innerHTML = '<option value="" disabled selected>Seleccione variante</option>';
                        if (data && data.variantes && data.variantes.length > 0) {
                            data.variantes.forEach(v => {
                                const option = document.createElement('option');
                                option.value = v.id;
                                option.textContent = v.nombre_variante;
                                varSelect.appendChild(option);
                            });
                        }
                    } catch (err) {
                        console.error('Error fetching variants', err);
                        varSelect.innerHTML = '<option value="" disabled selected>Seleccione variante</option>';
                    }
                }
            }
        });
    }

    const getVariantExtrasHtml = (categoryName) => {
        const escapeHtml = (unsafe) => {
            if (unsafe === null || unsafe === undefined) return '';
            return String(unsafe)
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
        };

        const n = (categoryName || '').toLowerCase();
        const field = (label, name, type = 'text', value = '') => `
            <div class="col-md-6 mb-3">
                <label class="form-label">${label}</label>
                <input type="${type}" name="${name}" class="form-control" placeholder="${label}" value="${escapeHtml(value)}" />
            </div>`;

        if (n.includes('ropa') || n.includes('camisa') || n.includes('vestido') || n.includes('calzado') || n.includes('zapato') || n.includes('cartera') || n.includes('carteras') || n.includes('bisuter') || n.includes('bisutería')) {
            return `<div class="row">${field('Talla', 'talla', 'text')}${field('Color', 'color', 'text')}</div>`;
        }

        if (n.includes('perfume') || n.includes('fragancia') || n.includes('colonia')) {
            return `<div class="row">${field('Volumen (ml)', 'volumen_ml', 'number')}${field('Fragancia', 'fragancia', 'text')}</div>`;
        }

        if (n.includes('cosmet') || n.includes('maquill') || n.includes('piel') || n.includes('spf')) {
            return `<div class="row">${field('SPF', 'spf', 'number')}${field('Tipo de piel', 'tipo_piel', 'text')}${field('Volumen (ml)', 'volumen_ml', 'number')}${field('Fragancia', 'fragancia', 'text')}</div>`;
        }

        return '';
    };

    const btnGuardarVR = document.getElementById('btnGuardarVarianteRapida');
    if (btnGuardarVR) {
        btnGuardarVR.addEventListener('click', async () => {
            const form = document.getElementById('formVarianteRapida');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);
            btnGuardarVR.disabled = true;
            btnGuardarVR.textContent = 'Guardando...';
            
            try {
                const response = await fetch('?c=productos&accion=addVariante', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success && data.variante) {
                    if (currentVarianteSelect) {
                        const option = document.createElement('option');
                        option.value = data.variante.id;
                        option.textContent = data.variante.nombre_variante;
                        currentVarianteSelect.appendChild(option);
                        currentVarianteSelect.value = data.variante.id;
                    }
                    const modalEl = document.getElementById('modalAgregarVarianteRapida');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    // Alert success
                    alert('Variante agregada exitosamente.');
                } else {
                    alert(data.error || 'Error al guardar la variante.');
                }
            } catch (err) {
                console.error(err);
                alert('Ocurrió un error en la conexión.');
            } finally {
                btnGuardarVR.disabled = false;
                btnGuardarVR.textContent = 'Guardar';
            }
        });
    }

    // Modal details for entries (Entradas)
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.ver-detalle-entrada');
        if (btn) {
            const id = btn.getAttribute('data-id');
            const modalEl = document.getElementById('entradasInsumoModal');
            const contenido = document.getElementById('contenidoEntradasInsumo');
            
            if (modalEl && contenido) {
                contenido.innerHTML = '<div class="text-center p-3"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando detalles...</p></div>';
                
                // Ensure we don't create multiple instances if not needed, but Bootstrap 5 handles it well
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (!modal) {
                    modal = new bootstrap.Modal(modalEl);
                }
                modal.show();
                
                try {
                    const response = await fetch('?c=entradas&accion=view_detalles&id=' + id);
                    if (response.ok) {
                        const html = await response.text();
                        contenido.innerHTML = html;
                    } else {
                        contenido.innerHTML = '<div class="alert alert-danger">Error en la respuesta del servidor.</div>';
                    }
                } catch (err) {
                    console.error('Error fetching entry details:', err);
                    contenido.innerHTML = '<div class="alert alert-danger">Error de conexión al cargar los detalles.</div>';
                }
            }
        }
    });
});
