$(document).ready(function() {

    const modalVerificar = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalVerificarPagos'));
    
    // Al abrir modal
    $(document).on('click', '.btn-ver-pagos', function() {
        const idNota = $(this).data('id');
        $('#lblNotaId').text(idNota);
        
        cargarPagosIndividuales(idNota);
        modalVerificar.show();
    });

    function cargarPagosIndividuales(idNota) {
        const tbody = $('#tbodyPagosIndividuales');
        tbody.html('<tr><td colspan="7" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>');
        
        $.ajax({
            url: '?c=Pagos&accion=ajaxGetPagos',
            type: 'GET',
            data: { id_nota: idNota },
            success: function(response) {
                if (response.success) {
                    tbody.empty();
                    const pagos = response.pagos;
                    
                    if (pagos.length === 0) {
                        tbody.html('<tr><td colspan="7" class="text-center text-muted py-3">No hay pagos registrados para esta nota.</td></tr>');
                        return;
                    }

                    pagos.forEach(p => {
                        let badgeClass = '';
                        switch(p.estado) {
                            case 'verificado': badgeClass = 'bg-success'; break;
                            case 'por verificar': badgeClass = 'bg-warning text-dark'; break;
                            case 'rechazado': badgeClass = 'bg-danger'; break;
                            default: badgeClass = 'bg-secondary';
                        }
                        
                        const montoStr = parseFloat(p.monto_usd) > 0 ? 
                            `$${parseFloat(p.monto_usd).toFixed(2)}` : 
                            `${parseFloat(p.monto_bs).toFixed(2)} Bs`;

                        const tr = `
                            <tr id="row-pago-${p.id}">
                                <td class="fw-bold">#${p.id}</td>
                                <td>${p.fecha}</td>
                                <td>${p.metodo_pago_nombre || 'Desconocido'}</td>
                                <td>${p.referencia || 'N/A'}</td>
                                <td class="fw-bold">${montoStr}</td>
                                <td class="text-center">
                                    <span class="badge ${badgeClass} estado-badge fs-6 px-3 py-2">${p.estado.toUpperCase()}</span>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-success btn-sm btn-cambiar-estado m-1" data-id="${p.id}" data-estado="verificado">Aprobar</button>
                                    <button type="button" class="btn btn-danger btn-sm btn-cambiar-estado m-1" data-id="${p.id}" data-estado="rechazado">Rechazar</button>
                                </td>
                            </tr>
                        `;
                        tbody.append(tr);
                    });
                } else {
                    tbody.html(`<tr><td colspan="7" class="text-center text-danger py-3">${response.message || 'Error al cargar pagos'}</td></tr>`);
                }
            },
            error: function() {
                tbody.html('<tr><td colspan="7" class="text-center text-danger py-3">Error de conexión</td></tr>');
            }
        });
    }

    // Cambiar estado individual
    $(document).on('click', '.btn-cambiar-estado', function() {
        const btn = $(this);
        const id = btn.data('id');
        const estado = btn.data('estado');
        const row = $(`#row-pago-${id}`);
        const badge = row.find('.estado-badge');
        
        // Efecto visual de carga
        const prevHtml = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
        $('.btn-cambiar-estado').prop('disabled', true);

        $.ajax({
            url: '?c=Pagos&accion=ajaxUpdateEstado',
            type: 'POST',
            data: { id: id, estado: estado },
            success: function(response) {
                if (response.success) {
                    // Actualizar el badge visualmente sin recargar
                    badge.removeClass('bg-success bg-warning text-dark bg-danger bg-secondary');
                    badge.text(estado.toUpperCase());
                    
                    switch(estado) {
                        case 'verificado': badge.addClass('bg-success'); break;
                        case 'por verificar': badge.addClass('bg-warning text-dark'); break;
                        case 'rechazado': badge.addClass('bg-danger'); break;
                        default: badge.addClass('bg-secondary');
                    }

                    // Pequeña notificación visual
                    // alert('Estado actualizado correctamente');
                } else {
                    alert('Error: ' + (response.message || 'Error al actualizar'));
                }
            },
            error: function() {
                alert('Problema de conexión al intentar cambiar el estado');
            },
            complete: function() {
                btn.html(prevHtml);
                $('.btn-cambiar-estado').prop('disabled', false);
            }
        });
    });

    // Recargar la página principal al cerrar el modal por si cambiaron los conteos generales
    $('#modalVerificarPagos').on('hidden.bs.modal', function () {
        // En una SPA real actualizaríamos la fila en el DataTable.
        // Aquí hacemos un reload para asegurar integridad.
        location.reload();
    });
});
