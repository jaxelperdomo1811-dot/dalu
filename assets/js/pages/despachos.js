document.addEventListener("DOMContentLoaded", function() {
    let detalleIndex = 1;
    const container = document.getElementById('detallesContainer');
    const btnAdd = document.getElementById('addDetalle');
    const totalSpan = document.getElementById('totalDespacho');

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.detalle-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.input-cantidad').value) || 0;
            const price = parseFloat(row.querySelector('.input-precio').value) || 0;
            total += (qty * price);
        });
        totalSpan.textContent = total.toFixed(2);
    }

    if (btnAdd) {
        btnAdd.addEventListener('click', function() {
            const firstSelect = container.querySelector('.select-producto').innerHTML;
            
            const newRow = document.createElement('div');
            newRow.className = 'detalle-row row g-2 mb-2 align-items-center';
            newRow.dataset.index = detalleIndex;
            
            newRow.innerHTML = `
                <div class="col-md-5">
                    <select name="detalles[${detalleIndex}][id_producto]" class="form-select form-select-sm select-producto" required>
                        ${firstSelect}
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="detalles[${detalleIndex}][cantidad]" class="form-control form-control-sm input-cantidad" placeholder="Cant." min="1" required>
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="detalles[${detalleIndex}][precio_unitario]" class="form-control form-control-sm input-precio" placeholder="Precio U." required readonly>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-sm btn-danger btn-remove-detalle">X</button>
                </div>
            `;
            
            container.appendChild(newRow);
            detalleIndex++;
            updateTotal();
        });
    }

    container?.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-detalle')) {
            if (container.children.length > 1) {
                e.target.closest('.detalle-row').remove();
                updateTotal();
            } else {
                alert("Debe haber al menos un producto.");
            }
        }
    });

    container?.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-producto')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.dataset.precio || 0;
            const row = e.target.closest('.detalle-row');
            row.querySelector('.input-precio').value = price;
            updateTotal();
        }
    });

    container?.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-cantidad')) {
            updateTotal();
        }
    });

    // Cargar detalles de despacho
    const btnsVerDetalles = document.querySelectorAll('.btn-ver-detalles');
    btnsVerDetalles.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const tbody = document.getElementById('tbodyDetalles');
            const totalModal = document.getElementById('detalleModalTotal');
            
            tbody.innerHTML = '<tr><td colspan="4" class="text-center">Cargando...</td></tr>';
            const modal = new bootstrap.Modal(document.getElementById('modalDetallesDespacho'));
            modal.show();

            fetch(`?c=Despacho&accion=detalles&id=${id}`)
                .then(res => res.json())
                .then(data => {
                    tbody.innerHTML = '';
                    let total = 0;
                    if (data.length > 0) {
                        data.forEach(item => {
                            const subtotal = item.cantidad * item.precio_unitario;
                            total += subtotal;
                            tbody.innerHTML += `
                                <tr>
                                    <td>${item.producto_nombre} ${item.variante_nombre ? `(${item.variante_nombre})` : ''}</td>
                                    <td>${item.cantidad}</td>
                                    <td>$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                                    <td>$${subtotal.toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        totalModal.textContent = `$${total.toFixed(2)}`;
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay detalles</td></tr>';
                        totalModal.textContent = '$0.00';
                    }
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error al cargar detalles</td></tr>';
                });
        });
    });
});
