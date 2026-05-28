document.addEventListener('DOMContentLoaded', () => {
    const selectAdd = document.querySelector('#modalAgregar select[name="id_categoria"]');
    const dynamicContainer = document.getElementById('dynamic-attributes');

    if (!selectAdd || !dynamicContainer) return;

    const escapeHtml = (value) => String(value || '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    const getCategoryName = () => {
        const opt = selectAdd.options[selectAdd.selectedIndex];
        return opt ? opt.text : '';
    };

    const productFields = () => `
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="form-label">Precio oferta</label>
                <input type="number" step="0.01" min="0" name="precio_oferta" class="form-control" placeholder="Precio oferta" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Stock mínimo</label>
                <input type="number" min="0" name="stock_minimo" class="form-control" placeholder="Stock mínimo" value="3" />
            </div>
            <div class="col-md-4">
                <label class="form-label">Imagen principal</label>
                <input type="file" accept="image/*" name="imagen" class="form-control" />
            </div>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Variantes</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-variant-btn">Agregar variante</button>
            </div>
            <small class="text-muted">Cada variante se guarda como fila separada. Ejemplo: talla L, color rojo.</small>
        </div>
        <div id="variant-rows"></div>`;

    const getVariantExtrasHtml = (categoryName, index, values = {}) => {
        const n = categoryName.toLowerCase();
        const field = (label, name, type = 'text', value = '') => `
            <div class="col-md-6 mb-3">
                <label class="form-label">${label}</label>
                <input type="${type}" name="variantes[${index}][${name}]" class="form-control" placeholder="${label}" value="${escapeHtml(value)}" />
            </div>`;

        if (n.includes('ropa') || n.includes('camisa') || n.includes('vestido') || n.includes('calzado') || n.includes('zapato') || n.includes('cartera') || n.includes('carteras') || n.includes('bisuter') || n.includes('bisutería')) {
            return `<div class="row">${field('Talla', 'talla', 'text', values.talla)}${field('Color', 'color', 'text', values.color)}</div>`;
        }

        if (n.includes('perfume') || n.includes('fragancia') || n.includes('colonia')) {
            return `<div class="row">${field('Volumen (ml)', 'volumen_ml', 'number', values.volumen_ml)}${field('Fragancia', 'fragancia', 'text', values.fragancia)}</div>`;
        }

        if (n.includes('cosmet') || n.includes('maquill') || n.includes('piel') || n.includes('spf')) {
            return `<div class="row">${field('SPF', 'spf', 'number', values.spf)}${field('Tipo de piel', 'tipo_piel', 'text', values.tipo_piel)}${field('Volumen (ml)', 'volumen_ml', 'number', values.volumen_ml)}${field('Fragancia', 'fragancia', 'text', values.fragancia)}</div>`;
        }

        return '';
    };

    const buildVariantRow = (index, category, values = {}) => `
        <div class="card mb-3 variant-row" data-index="${index}">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Variante ${index + 1}</h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-variant-btn">Eliminar</button>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nombre variante</label>
                        <input type="text" name="variantes[${index}][nombre_variante]" class="form-control" placeholder="Ej. Principal" value="${escapeHtml(values.nombre_variante || 'Principal')}" required />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" min="0" name="variantes[${index}][stock]" class="form-control" placeholder="Stock" value="${escapeHtml(values.stock || '')}" required />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Precio adicional</label>
                        <input type="number" step="0.01" min="0" name="variantes[${index}][precio_adicional]" class="form-control" placeholder="Precio adicional" value="${escapeHtml(values.precio_adicional || '')}" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Imagen variante</label>
                        <input type="text" name="variantes[${index}][imagen_variante]" class="form-control" placeholder="URL imagen variante" value="${escapeHtml(values.imagen_variante || '')}" />
                    </div>
                </div>
                <div class="variant-extra-fields">${getVariantExtrasHtml(category, index, values)}</div>
            </div>
        </div>`;

    const addVariantRow = (values = {}) => {
        const rows = dynamicContainer.querySelector('#variant-rows');
        const index = rows.querySelectorAll('.variant-row').length;
        const wrapper = document.createElement('div');
        wrapper.innerHTML = buildVariantRow(index, getCategoryName(), values);
        rows.appendChild(wrapper.firstElementChild);
    };

    const reindexVariantRows = () => {
        const rows = Array.from(dynamicContainer.querySelectorAll('.variant-row'));
        const values = rows.map((row) => {
            const data = {};
            row.querySelectorAll('input, select').forEach((field) => {
                const name = field.name;
                const match = name.match(/^variantes\[(\d+)\]\[(.+)\]$/);
                if (match) {
                    data[match[2]] = field.value;
                }
            });
            return data;
        });

        dynamicContainer.querySelector('#variant-rows').innerHTML = '';
        values.forEach((variantValues) => addVariantRow(variantValues));
    };

    const updateVariantExtras = () => {
        const category = getCategoryName();
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
            if (extra) extra.innerHTML = getVariantExtrasHtml(category, index, values);
        });
    };

    const renderDynamicAttributes = () => {
        dynamicContainer.innerHTML = productFields();

        const addBtn = dynamicContainer.querySelector('#add-variant-btn');
        const rows = dynamicContainer.querySelector('#variant-rows');

        addBtn.addEventListener('click', () => addVariantRow());

        rows.addEventListener('click', (event) => {
            if (event.target.matches('.remove-variant-btn')) {
                const row = event.target.closest('.variant-row');
                if (row) {
                    row.remove();
                    reindexVariantRows();
                }
            }
        });

        if (rows.children.length === 0) {
            addVariantRow();
        }
    };

    selectAdd.addEventListener('change', () => {
        renderDynamicAttributes();
        updateVariantExtras();
    });

    if (selectAdd.value) {
        renderDynamicAttributes();
    }
    // Mostrar imagen en modal al hacer click en miniatura (delegación)
    document.addEventListener('click', (e) => {
        const target = e.target;
        if (target && target.classList && target.classList.contains('product-thumb')) {
            const src = target.getAttribute('data-src') || target.src;
            const imgEl = document.getElementById('imageModalImg');
            if (imgEl) imgEl.src = src;
            const modalEl = document.getElementById('imageModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        } else if (target && target.classList && target.classList.contains('view-variants-btn')) {
            const id = target.getAttribute('data-id');
            if (!id) return;
            const body = document.getElementById('variantsModalBody');
            if (body) body.innerHTML = 'Cargando...';
            fetch(`?c=productos&accion=viewVariantes&id=${encodeURIComponent(id)}&ajax=1`)
                .then(res => res.json())
                .then(data => {
                    if (!data) {
                        if (body) body.innerHTML = 'No se encontraron variantes.';
                        return;
                    }
                    let html = `<h6 class="mb-3">${data.nombre || 'Producto'}</h6>`;
                    if (!data.variantes || data.variantes.length === 0) {
                        html += '<p>No hay variantes</p>';
                    } else {
                        html += '<div class="row">';
                        data.variantes.forEach(v => {
                            const attrs = v.atributos || {};
                            let attrsHtml = '';
                            Object.keys(attrs).forEach(k => { attrsHtml += `<small class="d-block"><strong>${k}:</strong> ${attrs[k]}</small>`; });
                            const img = v.imagen_variante ? `<img src="${v.imagen_variante}" style="width:80px;height:80px;object-fit:cover;border-radius:4px;" />` : '';
                            html += `
                                <div class="col-md-6 mb-3">
                                    <div class="card p-2">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">${img}</div>
                                            <div>
                                                <div><strong>${v.nombre_variante}</strong></div>
                                                <div>Stock: ${v.stock}</div>
                                                <div>Precio adicional: ${v.precio_adicional}</div>
                                                ${attrsHtml}
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                        });
                        html += '</div>';
                    }
                    if (body) body.innerHTML = html;
                    const modalEl = document.getElementById('variantsModal');
                    if (modalEl && typeof bootstrap !== 'undefined') {
                        const modal = new bootstrap.Modal(modalEl);
                        modal.show();
                    }
                }).catch(() => {
                    if (body) body.innerHTML = 'Error cargando variantes.';
                });
        }
    });
});
