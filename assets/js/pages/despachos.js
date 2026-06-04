document.addEventListener("DOMContentLoaded", function() {
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
