/**
 * pedidos2.js – Lógica para agregar/eliminar detalles dinámicos en los modales de pedidos (con productos, imagenes y validación)
 */
document.addEventListener('DOMContentLoaded', () => {
    const PRODUCTS = window.PRODUCTS || [];

    function escapeHtml(s) {
        return String(s).replace(/[&<>\"']/g, (m) => ({'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#39;'})[m]);
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
            row.className = 'detalle-row d-flex gap-2 mb-2';
            row.setAttribute('data-index', idx);
            row.innerHTML = `
                <input type="hidden" name="detalles[${idx}][tipo]" value="producto">
                <select name="detalles[${idx}][id_producto]" class="form-select form-select-sm">${prodOpts}</select>
                <input type="text" name="detalles[${idx}][nombre_producto]" class="form-control" placeholder="Nombre producto (opcional)">
                <input type="number" name="detalles[${idx}][cantidad]" class="form-control" placeholder="Cantidad" min="1" value="1">
                <input type="text" name="detalles[${idx}][link]" class="form-control" placeholder="Link (opcional)">
                <input type="file" name="detalleImagens[${idx}]" accept="image/*" class="form-control form-control-sm">
                <button type="button" class="btn btn-sm btn-danger btn-remove-detalle">Eliminar</button>
            `;
            container.appendChild(row);
        });

        container.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-remove-detalle')) {
                const row = e.target.closest('.detalle-row');
                if (row) row.remove();
            }
        });
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
                const hasName = nombre && nombre.value.trim() !== '';
                const hasLink = link && link.value.trim() !== '';
                const hasFile = file && file.files && file.files.length > 0;
                const hasProduct = prodSelect && prodSelect.value !== '';
                if (!(hasName || hasLink || hasFile || hasProduct)) {
                    alert('Cada detalle debe tener al menos un nombre, link, imagen o producto seleccionado.');
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
});
