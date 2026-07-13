document.addEventListener('DOMContentLoaded', () => {
    const escapeHtml = (value) => String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const initProductModal = (modal) => {
        const selectCat = modal.querySelector('select[name="id_categoria"]');
        const isEdit = modal.classList.contains('modal-edit-product');
        const productId = isEdit ? modal.querySelector('input[name="id"]').value : null;
        const dynamicContainer = isEdit
            ? modal.querySelector(`#dynamic-attributes-edit-${productId}`)
            : modal.querySelector('#dynamic-attributes');

        fetch('?c=productos&accion=log_form', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                log: 'initProductModal',
                isEdit: isEdit,
                productId: productId,
                selectCatFound: !!selectCat,
                dynamicContainerFound: !!dynamicContainer,
                selectCatValue: selectCat ? selectCat.value : null
            })
        });

        if (!selectCat || !dynamicContainer) return;

        let existingVariants = [];
        try {
            existingVariants = isEdit ? JSON.parse(modal.getAttribute('data-variantes') || '[]') : [];
        } catch (e) {
            fetch('?c=productos&accion=log_form', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ log: 'JSON parse error', error: e.message, data: modal.getAttribute('data-variantes') })
            });
        }
        const stockMinimo = isEdit ? modal.getAttribute('data-stock-minimo') || '3' : '3';

        // Crear contenedor para registrar IDs de variantes eliminadas
        let deletedVariantsContainer = modal.querySelector('.deleted-variants-container');
        if (isEdit && !deletedVariantsContainer) {
            deletedVariantsContainer = document.createElement('div');
            deletedVariantsContainer.className = 'deleted-variants-container';
            modal.querySelector('form').appendChild(deletedVariantsContainer);
        }

        const getCategoryName = () => {
            const opt = selectCat.options[selectCat.selectedIndex];
            return opt ? opt.text : '';
        };

        const productFields = (sMinimo = '3') => `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Stock mínimo</label>
                    <input type="number" min="0" name="stock_minimo" class="form-control" placeholder="Stock mínimo" value="${escapeHtml(sMinimo)}" />
                </div>
                <div class="col-md-6">
                    <label class="form-label">${isEdit ? 'Nueva Imagen principal (Opcional)' : 'Imagen principal'}</label>
                    <input type="file" accept="image/*" name="imagen" class="form-control" />
                </div>
            </div>
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Variantes</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary add-variant-btn">Agregar variante</button>
                </div>
                <small class="text-muted">Cada variante se guarda como fila separada. Ejemplo: talla L, color rojo.</small>
            </div>
            <div class="variant-rows"></div>`;

        let dynamicCampos = [];

        const renderVariantExtrasHtml = (index, values = {}) => {
            let html = '<div class="row">';
            dynamicCampos.forEach(campo => {
                let type = campo.tipo || 'text';
                let label = campo.nombre.charAt(0).toUpperCase() + campo.nombre.slice(1);
                let val = escapeHtml(values[campo.nombre] || '');
                html += `
                <div class="col-md-6 mb-3">
                    <label class="form-label">${label}</label>
                    <input type="${type}" name="variantes[${index}][${campo.nombre}]" class="form-control" placeholder="${label}" value="${val}" />
                </div>`;
            });
            html += '</div>';
            return html;
        };

        const buildVariantRow = (index, values = {}) => {
            const idInput = values.id ? `<input type="hidden" name="variantes[${index}][id]" value="${escapeHtml(values.id)}" />` : '';
            return `
            <div class="card mb-3 variant-row" data-index="${index}" data-variant-id="${values.id || ''}">
                <div class="card-body">
                    ${idInput}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">Variante ${index + 1}</h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-variant-btn">Eliminar</button>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-nowrap overflow-hidden">Código</label>
                            <input type="text" name="variantes[${index}][codigo_producto]" class="form-control" placeholder="Código" value="${escapeHtml(values.codigo_producto || '')}" />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Nombre variante</label>
                            <input type="text" name="variantes[${index}][nombre_variante]" class="form-control" placeholder="Ej. Principal" value="${escapeHtml(values.nombre_variante || 'Principal')}" required />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Stock</label>
                            <input type="number" min="0" name="variantes[${index}][stock]" class="form-control" placeholder="Stock" value="${escapeHtml(values.stock || '')}" required />
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Precio adicional</label>
                            <input type="number" step="0.01" min="0" name="variantes[${index}][precio_adicional]" class="form-control" placeholder="Precio adicional" value="${escapeHtml(values.precio_adicional || '')}" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Imagen variante (Opcional)</label>
                            ${values.imagen_variante ? `
                                <div class="mb-2">
                                    <img src="${escapeHtml(values.imagen_variante)}" onerror="this.src='assets/img/products/default.jpeg'" alt="imagen variante" class="img-thumbnail product-thumb" style="width:100px;height:100px;object-fit:cover;cursor:pointer;" data-src="${escapeHtml(values.imagen_variante)}" />
                                </div>
                            ` : ''}
                            <input type="hidden" name="variantes[${index}][imagen_variante_actual]" value="${escapeHtml(values.imagen_variante || '')}" />
                            <input type="file" accept="image/*" name="imagen_variante[${index}]" class="form-control" />
                        </div>
                    </div>
                    <div class="variant-extra-fields">${renderVariantExtrasHtml(index, values)}</div>
                </div>
            </div>`;
        };

        const addVariantRow = (values = {}) => {
            const rows = dynamicContainer.querySelector('.variant-rows');
            const index = rows.querySelectorAll('.variant-row').length;
            const wrapper = document.createElement('div');
            const normalizedValues = { ...values };
            if (values.atributos && typeof values.atributos === 'object') {
                Object.assign(normalizedValues, values.atributos);
            }
            wrapper.innerHTML = buildVariantRow(index, normalizedValues);
            rows.appendChild(wrapper.firstElementChild);
        };

        const reindexVariantRows = () => {
            const rows = Array.from(dynamicContainer.querySelectorAll('.variant-row'));
            rows.forEach((row, newIndex) => {
                row.setAttribute('data-index', newIndex);

                const header = row.querySelector('h6');
                if (header) {
                    header.textContent = `Variante ${newIndex + 1}`;
                }

                row.querySelectorAll('input, select').forEach((field) => {
                    const name = field.name;
                    if (!name) return;
                    let match = name.match(/^variantes\[(\d+)\]\[(.+)\]$/);
                    if (match) {
                        field.name = `variantes[${newIndex}][${match[2]}]`;
                    } else {
                        match = name.match(/^imagen_variante\[(\d+)\]$/);
                        if (match) {
                            field.name = `imagen_variante[${newIndex}]`;
                        }
                    }
                });
            });
        };

        const updateVariantExtras = () => {
            const rows = Array.from(dynamicContainer.querySelectorAll('.variant-row'));
            rows.forEach((row, index) => {
                const values = {};
                row.querySelectorAll('input, select').forEach((field) => {
                    const match = field.name.match(/^variantes\[(\d+)\]\[(.+)\]$/);
                    if (match) {
                        values[match[2]] = field.value;
                    }
                });
                const extra = row.querySelector('.variant-extra-fields');
                if (extra) extra.innerHTML = renderVariantExtrasHtml(index, values);
            });
        };

        const renderDynamicAttributes = (initial = false) => {
            dynamicContainer.innerHTML = productFields(stockMinimo);

            const addBtn = dynamicContainer.querySelector('.add-variant-btn');
            const rows = dynamicContainer.querySelector('.variant-rows');

            addBtn.addEventListener('click', () => addVariantRow());

            rows.addEventListener('click', (event) => {
                if (event.target.matches('.remove-variant-btn')) {
                    const row = event.target.closest('.variant-row');
                    if (row) {
                        const variantId = row.getAttribute('data-variant-id');
                        if (variantId && isEdit) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'deleted_variants[]';
                            input.value = variantId;
                            deletedVariantsContainer.appendChild(input);
                        }
                        row.remove();
                        reindexVariantRows();
                    }
                }
            });

            if (initial && isEdit && existingVariants.length > 0) {
                existingVariants.forEach(v => addVariantRow(v));
            } else {
                if (rows.children.length === 0) {
                    addVariantRow();
                }
            }
        };

        const updateCamposAndRender = async (initial = false) => {
            const catId = selectCat.value;
            if (!catId) {
                dynamicCampos = [];
                renderDynamicAttributes(initial);
                return;
            }
            try {
                const res = await fetch(`?c=productos&accion=getCamposCategoria&id_categoria=${catId}`);
                if (res.ok) {
                    dynamicCampos = await res.json();
                } else {
                    dynamicCampos = [];
                }
            } catch (e) {
                console.error(e);
                dynamicCampos = [];
            }
            renderDynamicAttributes(initial);
            updateVariantExtras();
        };

        selectCat.addEventListener('change', () => {
            updateCamposAndRender(false);
        });

        if (selectCat.value) {
            updateCamposAndRender(true);
        }
    };

    // Inicializar modal de agregar
    const addModal = document.getElementById('modalAgregar');
    if (addModal) {
        try {
            initProductModal(addModal);
        } catch (e) {
            console.error('Error init addModal', e);
        }
    }

    // Inicializar modales de editar
    const editModals = document.querySelectorAll('.modal-edit-product');
    editModals.forEach(modal => {
        try {
            initProductModal(modal);
        } catch (e) {
            fetch('?c=productos&accion=log_form', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ log: 'Crash in initProductModal', error: e.message, stack: e.stack, modalId: modal.id })
            });
            console.error('Error in initProductModal:', e);
        }
    });

    // Mostrar imagen en modal al hacer click en miniatura (delegación)
    document.addEventListener('click', (e) => {
        const target = e.target;
        if (target && target.classList && target.classList.contains('product-thumb')) {
            const src = target.getAttribute('data-src') || target.src;
            const imgEl = document.getElementById('imageModalImg');
            if (imgEl) imgEl.src = src;
            const modalEl = document.getElementById('imageModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }
        } else if (target && target.classList && target.classList.contains('view-variants-btn')) {
            const productId = target.getAttribute('data-id');
            const modalBody = document.getElementById('variantsModalBody');
            const modalEl = document.getElementById('variantsModal');

            if (modalBody && modalEl && productId) {
                modalBody.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"></div><p class="mt-2">Cargando variantes...</p></div>';

                const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();

                fetch(`?c=productos&accion=viewVariantes&id=${productId}`)
                    .then(response => response.text())
                    .then(html => {
                        modalBody.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error fetching variants:', error);
                        modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar las variantes.</div>';
                    });
            }
        }
    });

    window.abrirModalCrearCampo = function(btn = null) {
        if (btn) {
            window._currentCamposContainer = btn.closest('.mb-3').querySelector('.campos-container');
        } else {
            window._currentCamposContainer = null;
        }
        const modalEl = document.getElementById('modalCrearCampo');
        if (modalEl && typeof bootstrap !== 'undefined') {
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            document.getElementById('nuevo_campo_nombre').value = '';
            document.getElementById('nuevo_campo_tipo').value = 'text';
            modal.show();
        }
    };

    window.guardarNuevoCampo = function() {
        const nombre = document.getElementById('nuevo_campo_nombre').value.trim();
        const tipo = document.getElementById('nuevo_campo_tipo').value;
        if (!nombre) {
            alert("El nombre es requerido");
            return;
        }

        const formData = new URLSearchParams();
        formData.append('nombre', nombre);
        formData.append('tipo', tipo);

        fetch('?c=categorias&accion=createCampo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString()
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add to all campos containers
                const containers = document.querySelectorAll('.campos-container');
                containers.forEach(container => {
                    const html = `
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="campos[]" value="${data.id}" id="add_campo_new_${data.id}">
                            <label class="form-check-label" for="add_campo_new_${data.id}">
                                ${escapeHtml(data.nombre)}
                            </label>
                        </div>
                    </div>`;
                    container.insertAdjacentHTML('beforeend', html);
                });
                
                // Also check it in the current container
                if (window._currentCamposContainer) {
                    const newlyAdded = window._currentCamposContainer.querySelector(`input[value="${data.id}"]`);
                    if (newlyAdded) newlyAdded.checked = true;
                } else {
                    // Si se creó desde la tabla de campos, recargar para verlo en la tabla
                    window.location.reload();
                    return;
                }
                
                const modalEl = document.getElementById('modalCrearCampo');
                bootstrap.Modal.getInstance(modalEl).hide();
            } else {
                alert(data.error || 'Error al crear el campo');
            }
        })
        .catch(err => {
            console.error(err);
            alert("Ocurrió un error");
        });
    };
});
