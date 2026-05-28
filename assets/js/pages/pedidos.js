/**
 * pedidos.js – Lógica para agregar/eliminar detalles dinámicos en los modales de pedidos
 */
document.addEventListener('DOMContentLoaded', () => {
	function initDetalles(containerId, addBtnId) {
		const container = document.getElementById(containerId);
		const addBtn = document.getElementById(addBtnId);
		if (!container || !addBtn) return;

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
				<input type="text" name="detalles[${idx}][nombre_producto]" class="form-control" placeholder="Nombre producto (opcional)">
				<input type="number" name="detalles[${idx}][cantidad]" class="form-control" placeholder="Cantidad" min="1" value="1">
				<input type="text" name="detalles[${idx}][link]" class="form-control" placeholder="Link (opcional)">
				<input type="hidden" name="detalles[${idx}][estado]" value="pendiente">
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

	initDetalles('detallesContainerTienda', 'addDetalleTienda');
	initDetalles('detallesContainerCliente', 'addDetalleCliente');
});
