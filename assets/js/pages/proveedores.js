/**
 * proveedores.js
 */
document.addEventListener('DOMContentLoaded', () => {
    // API Cedula
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
                        const modal = cedulaInput.closest('.modal') || document;
                        const nInput = modal.querySelector('input[name="nombre"]');
                        const aInput = modal.querySelector('input[name="apellido"]');
                        const rInput = modal.querySelector('input[name="rif"]');
                        const btnSubmit = modal.querySelector('button[type="submit"]');

                        if (data.exists) {
                            const persona = data.data || {};
                            if (nInput) nInput.value = persona.nombre_completo || '';
                            if (aInput) aInput.value = '';
                            if (rInput && persona.rif) rInput.value = persona.rif || '';

                            mensajeCedula.style.color = 'green';
                            mensajeCedula.innerText = 'Datos encontrados.';
                            if (btnSubmit) btnSubmit.disabled = false;
                        } else {
                            mensajeCedula.style.color = data.valid_format ? 'orange' : 'red';
                            mensajeCedula.innerText = data.message || 'Documento no encontrado.';
                            if (nInput) nInput.value = '';
                            if (aInput) aInput.value = '';
                            if (rInput) rInput.value = '';
                            if (btnSubmit) btnSubmit.disabled = false;
                        }
                    } catch (e) {
                        console.error('Invalid JSON response', textData);
                        mensajeCedula.style.color = 'red';
                        mensajeCedula.innerText = 'Error al parsear la respuesta.';
                        const modal = cedulaInput.closest('.modal') || document;
                        const btnSubmit = modal.querySelector('button[type="submit"]');
                        if (btnSubmit) btnSubmit.disabled = false;
                    }
                } catch (error) {
                    console.error('Error al consultar documento:', error);
                    mensajeCedula.style.color = 'red';
                    mensajeCedula.innerText = 'Error al conectar con la API.';
                    const modal = cedulaInput.closest('.modal') || document;
                    const btnSubmit = modal.querySelector('button[type="submit"]');
                    if (btnSubmit) btnSubmit.disabled = false;
                }
            } else {
                mensajeCedula.innerText = '';
                const modal = cedulaInput.closest('.modal') || document;
                const btnSubmit = modal.querySelector('button[type="submit"]');
                if (btnSubmit) btnSubmit.disabled = false;
            }
        });
    }

    // Validación de RIF (auto-uppercase y limpieza)
    document.querySelectorAll('input[name="rif"]').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase().replace(/[^VEJPG0-9-]/g, '');
        });
    });

    // Dynamic Rows Logic for Entradas
    const btnAddProducto = document.getElementById('btn_add_producto');
    const container = document.getElementById('productos_container');
    let currentVarianteSelect = null;
    let currentProductoSelect = null;

    if (btnAddProducto && container) {
        btnAddProducto.addEventListener('click', () => {
            const row = container.querySelector('.producto-row');
            if (row) {
                const prodSelectEl = row.querySelector('.producto-select');
                const varSelectEl = row.querySelector('.variante-select');

                const $firstProd = $(prodSelectEl);
                const $firstVar = $(varSelectEl);

                // Destruir select2 temporalmente en la primera fila para clonar HTML limpio
                if (typeof $.fn.select2 === 'function') {
                    if ($firstProd.hasClass('select2-hidden-accessible')) {
                        $firstProd.select2('destroy');
                    }
                    if ($firstVar.hasClass('select2-hidden-accessible')) {
                        $firstVar.select2('destroy');
                    }
                }

                // Clonar la fila limpia
                const newRow = row.cloneNode(true);

                // Resetear inputs de texto/número en la nueva fila
                newRow.querySelectorAll('input').forEach(input => {
                    input.value = '';
                });

                // Resetear selects en la nueva fila para que estén vacíos
                const newProdSelect = newRow.querySelector('.producto-select');
                const newVarSelect = newRow.querySelector('.variante-select');

                if (newProdSelect) {
                    newProdSelect.value = '';
                    newProdSelect.removeAttribute('data-categoria');
                }

                if (newVarSelect) {
                    newVarSelect.innerHTML = '<option value="" disabled selected>Seleccione variante</option>';
                    newVarSelect.value = '';
                }

                container.appendChild(newRow);

                // Re-inicializar select2 para todas las filas
                if (typeof initSelect2 === 'function') {
                    initSelect2();
                }
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
            
            if (e.target.classList.contains('btn-add-producto') || e.target.closest('.btn-add-producto')) {
                const row = e.target.closest('.producto-row');
                currentProductoSelect = row.querySelector('.producto-select');
                document.getElementById('formProductoRapido').reset();
                const modalEl = document.getElementById('modalAgregarProductoRapido');
                if (modalEl) {
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    modal.show();
                }
            }
            
            if (e.target.classList.contains('btn-add-variante') || e.target.closest('.btn-add-variante')) {
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
                    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
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
                                const codigo = v.codigo_producto ? `(Cód: ${v.codigo_producto}) ` : '';
                                option.textContent = codigo + v.nombre_variante;
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

    const prCategoria = document.getElementById('pr_categoria');
    if (prCategoria) {
        prCategoria.addEventListener('change', function() {
            const catName = this.options[this.selectedIndex].text;
            const container = document.getElementById('pr_dynamic_attributes');
            if (container) {
                container.innerHTML = getVariantExtrasHtml(catName);
            }
        });
    }

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

    const btnGuardarPR = document.getElementById('btnGuardarProductoRapido');
    if (btnGuardarPR) {
        btnGuardarPR.addEventListener('click', async () => {
            const form = document.getElementById('formProductoRapido');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const formData = new FormData(form);
            
            // Recoger los campos dinámicos
            const dynamicContainer = document.getElementById('pr_dynamic_attributes');
            if (dynamicContainer) {
                const inputs = dynamicContainer.querySelectorAll('input, select');
                const atributos = {};
                inputs.forEach(input => {
                    if (input.name && input.value) {
                        atributos[input.name] = input.value;
                    }
                });
                formData.append('atributos', JSON.stringify(atributos));
            }
            
            btnGuardarPR.disabled = true;
            btnGuardarPR.textContent = 'Guardando...';
            
            try {
                const response = await fetch('?c=productos&accion=addProducto', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                
                if (data.success && data.producto) {
                    const newOption = new Option(data.producto.nombre, data.producto.id, false, true);
                    if (currentProductoSelect) {
                        currentProductoSelect.add(newOption);
                        currentProductoSelect.value = data.producto.id;
                        currentProductoSelect.dispatchEvent(new Event('change', { bubbles: true }));
                    } else {
                        document.querySelectorAll('.producto-select').forEach(sel => {
                            sel.add(new Option(data.producto.nombre, data.producto.id));
                        });
                    }
                    
                    const modalEl = document.getElementById('modalAgregarProductoRapido');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    
                    alert('Producto agregado exitosamente.');
                } else {
                    alert(data.error || 'Error al guardar el producto.');
                }
            } catch (err) {
                console.error(err);
                alert('Ocurrió un error en la conexión.');
            } finally {
                btnGuardarPR.disabled = false;
                btnGuardarPR.textContent = 'Guardar';
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
                    modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                }
                modal.show();
                
                try {
                    const response = await fetch('?c=compraProductos&accion=view_detalles&id=' + id);
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

// --- Configuración de Intro.js para Proveedores ---
document.addEventListener('DOMContentLoaded', () => {
    const btnAyuda = document.getElementById('btnAyudaInteractiva');
    if (btnAyuda) {
        btnAyuda.addEventListener('click', (e) => {
            e.preventDefault();
            const tour = introJs();
            tour.setOptions({
                nextLabel: 'Siguiente',
                prevLabel: 'Anterior',
                doneLabel: 'Entendido',
                exitOnOverlayClick: false,
                steps: [
                    {
                        title: "Módulo de Proveedores",
                        intro: "Aquí puedes gestionar a los proveedores de la tienda y sus compras."
                    },
                    {
                        element: document.querySelector('button[data-bs-target="#agregarProveedorModal"]'),
                        intro: "Utiliza este botón para registrar un nuevo proveedor."
                    },
                    {
                        element: document.querySelector('button[data-bs-target="#agregarEntradaModal"]'),
                        intro: "Con este botón puedes registrar una Nueva Compra a un proveedor existente."
                    },
                    {
                        element: document.querySelector('.table-responsive'),
                        intro: "Aquí se listan todos tus proveedores. Puedes ver más detalles, editarlos o inactivarlos usando los botones de acción."
                    }
                ]
            });
            tour.start();
        });
    }
});
