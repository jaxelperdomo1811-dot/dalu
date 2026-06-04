/**
 * pedidos.js – Lógica para agregar/eliminar detalles dinámicos en los modales de pedidos (con productos, imagenes y validación)
 */
document.addEventListener('DOMContentLoaded', () => {
    const PRODUCTS = window.PRODUCTS || [];

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[m]);
    }

    function buildProductOptionsHtml() {
        let opts = '<option value="">-- Producto existente --</option>';
        PRODUCTS.forEach(p => {
            opts += `<option value="${p.id}">${escapeHtml(p.nombre)}</option>`;
        });
        return opts;
    }

    function initDetalles(containerId, addBtnId) {
        const container = document.getElementById(containerId);
        const addBtn = document.getElementById(addBtnId);
        if (!container || !addBtn) return;

        const prodOpts = buildProductOptionsHtml();

        const getNextIndex = () => {
            const rows = container.querySelectorAll('.detalle-row');
            let max = -1;
            rows.forEach(r => {
                const i = parseInt(r.getAttribute('data-index'), 10);
                if (!isNaN(i) && i > max) max = i;
            });
            return max + 1;
        };

        addBtn.addEventListener('click', () => {
            const idx = getNextIndex();
            const row = document.createElement('div');
            row.className = 'detalle-row d-flex gap-2 mb-2 align-items-center';
            row.setAttribute('data-index', idx);
            row.innerHTML = `
                <input type="hidden" name="detalles[${idx}][tipo]" value="producto">
                <input type="hidden" name="detalles[${idx}][estado]" value="pendiente">
                <div style="width: 15%;">
                    <select name="detalles[${idx}][id_producto]" class="form-select form-select-sm sel-producto w-100">${prodOpts}</select>
                </div>
                <div style="width: 18%;">
                    <select name="detalles[${idx}][id_variante]" class="form-select form-select-sm sel-variante no-select2 w-100"><option value="">-- Variante --</option></select>
                </div>
                <div style="width: 15%;">
                    <input type="text" name="detalles[${idx}][nombre_producto]" class="form-control form-control-sm w-100" placeholder="Nombre (opc)">
                </div>
                <div style="width: 8%;">
                    <input type="number" name="detalles[${idx}][cantidad]" class="form-control form-control-sm input-cantidad-detalle in-cantidad w-100" placeholder="Cant." min="1" value="1">
                </div>
                <div style="width: 10%;">
                    <input type="number" step="0.01" name="detalles[${idx}][precio_unitario]" class="form-control form-control-sm input-precio-detalle in-precio w-100" placeholder="Precio $" required>
                </div>
                <div style="width: 15%;">
                    <input type="text" name="detalles[${idx}][link]" class="form-control form-control-sm input-link w-100" placeholder="Link (opc)">
                </div>
                <div style="width: 14%;">
                    <input type="file" name="detalleImagens[${idx}]" accept="image/*" class="form-control form-control-sm input-file w-100">
                </div>
                <div style="width: 5%;" class="text-end">
                    <button type="button" class="btn btn-sm btn-danger btn-remove-detalle">X</button>
                </div>
            `;
            container.appendChild(row);
            if (typeof initSelect2 === 'function') initSelect2();

            // Eventos para la nueva fila
            const selProd = row.querySelector('.sel-producto');
            const selVar = row.querySelector('.sel-variante');
            const inCant = row.querySelector('.in-cantidad');
            const inPre = row.querySelector('.in-precio');
            const inLink = row.querySelector('.input-link');
            const inFile = row.querySelector('.input-file');

            $(selProd).on('change', (e) => {
                const prodId = e.target.value;
                selVar.innerHTML = '<option value="">-- Variante --</option>';
                inPre.value = '';
                
                if (prodId && window.PRODUCTS) {
                    const prod = window.PRODUCTS.find(p => p.id == prodId);
                    
                    if (prod && prod.precio_venta) {
                        inPre.value = prod.precio_venta;
                    }
                    
                    if (prod && prod.variantes) {
                        prod.variantes.forEach(v => {
                            const precioBase = parseFloat(prod.precio_venta || 0);
                            const precioAdicional = parseFloat(v.precio_adicional || 0);
                            const precioTotalVariante = precioBase + precioAdicional;
                            
                            selVar.innerHTML += `<option value="${v.id}" data-precio="${precioTotalVariante}" data-stock="${v.stock}">${v.nombre_variante} (Disp: ${v.stock})</option>`;
                        });
                    }
                }
                inPre.dispatchEvent(new Event('input', { bubbles: true }));
            });

            $(selVar).on('change', (e) => {
                const selected = selVar.options[selVar.selectedIndex];
                if (selected && selected.dataset.precio) {
                    inPre.value = selected.dataset.precio;
                    
                    if (selected.dataset.stock) {
                        inCant.max = selected.dataset.stock;
                        if (parseInt(inCant.value) > parseInt(selected.dataset.stock)) {
                            inCant.value = selected.dataset.stock;
                        }
                    }
                }
                inPre.dispatchEvent(new Event('input', { bubbles: true }));
            });

            // Si agrega link o imagen, descartar producto y variante
            const clearSelects = () => {
                if (inLink.value.trim() !== '' || (inFile.files && inFile.files.length > 0)) {
                    $(selProd).val('').trigger('change.select2');
                    selVar.innerHTML = '<option value="">-- Variante --</option>';
                }
            };

            inLink.addEventListener('input', clearSelects);
            inFile.addEventListener('change', clearSelects);
        });

        container.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-remove-detalle')) {
                const row = e.target.closest('.detalle-row');
                if (row) row.remove();
            }
        });

        // Add the first row automatically
        if (container.children.length === 0) {
            addBtn.click();
        }
    }

    function validateDetallesOnSubmit(formSelector, containerId) {
        const form = document.querySelector(formSelector);
        const container = document.getElementById(containerId);
        if (!form || !container) return;
        form.addEventListener('submit', (e) => {
            const rows = container.querySelectorAll('.detalle-row');
            for (const row of rows) {
                const nombre = row.querySelector('input[name^="detalles"][name$="[nombre_producto]"]');
                const link = row.querySelector('input[name^="detalles"][name$="[link]"]');
                const file = row.querySelector('input[type="file"]');
                const prodSelect = row.querySelector('select[name^="detalles"][name$="[id_producto]"]');
                const varSelect = row.querySelector('select[name^="detalles"][name$="[id_variante]"]');
                
                const hasName = nombre && nombre.value.trim() !== '';
                const hasLink = link && link.value.trim() !== '';
                const hasFile = file && file.files && file.files.length > 0;
                const hasProduct = prodSelect && prodSelect.value !== '';
                const hasVariant = varSelect && varSelect.value !== '';

                if (!(hasName || hasLink || hasFile || hasProduct)) {
                    alert('Cada detalle debe tener al menos un nombre, link, imagen o producto seleccionado.');
                    e.preventDefault();
                    return;
                }
                
                if (hasProduct && !hasVariant) {
                    alert('Debe seleccionar la variante para el producto seleccionado.');
                    e.preventDefault();
                    return;
                }
            }
        });
    }

    initDetalles('detallesContainerTienda','addDetalleTienda');
    initDetalles('detallesContainerCliente','addDetalleCliente');
    validateDetallesOnSubmit('form[action="?c=pedidos&accion=insertTienda"]','detallesContainerTienda');
    validateDetallesOnSubmit('form[action="?c=pedidos&accion=insertCliente"]','detallesContainerCliente');

    function initPedidoDetalles() {
        const modalEl = document.getElementById('modalDetallesPedido');
        const modalBody = document.getElementById('modalDetallesPedidoBody');
        const modalTitle = document.getElementById('modalDetallesPedidoTitulo');
        if (!modalEl || !modalBody || !modalTitle) return;

        const bsModal = new bootstrap.Modal(modalEl);

        document.body.addEventListener('click', (event) => {
            const button = event.target.closest('.btn-ver-detalles');
            if (!button) return;

            const pedidoId = button.getAttribute('data-id');
            if (!pedidoId) return;

            modalTitle.textContent = 'Detalle del pedido #' + pedidoId;
            modalBody.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div><p class="mt-3">Cargando detalle del pedido...</p></div>';
            bsModal.show();

            fetch(`?c=pedidos&accion=view_detalles&id=${encodeURIComponent(pedidoId)}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(() => {
                    modalBody.innerHTML = '<div class="alert alert-danger">Error al cargar los detalles del pedido.</div>';
                });
        });
    }

    initPedidoDetalles();

});
