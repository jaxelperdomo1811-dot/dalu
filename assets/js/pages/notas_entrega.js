document.addEventListener('DOMContentLoaded', () => {
    // 1. Lógica para buscar/registrar cliente por cédula en el modal (reutilizado de servicios)
    const pedidoCedula = document.getElementById('pedido_cedula_cliente');
    const pedidoTipoPersona = document.getElementById('pedido_tipo_persona');
    const mensajePedidoCedula = document.getElementById('mensaje_pedido_cedula');
    const pedidoNombreCliente = document.getElementById('pedido_nombre_cliente');
    const pedidoIdCliente = document.getElementById('pedido_id_cliente');

    if (pedidoCedula && pedidoTipoPersona) {
        pedidoCedula.addEventListener('blur', async () => {
            const cedula = pedidoCedula.value.trim();
            const tipo = pedidoTipoPersona.value;

            if (cedula.length >= 6 && tipo) {
                mensajePedidoCedula.style.color = 'blue';
                mensajePedidoCedula.innerText = 'Buscando cliente...';
                pedidoNombreCliente.value = '';
                pedidoIdCliente.value = '';
                pedidoNombreCliente.setAttribute('readonly', true);

                try {
                    const response = await fetch(`?c=clientes&accion=buscarYRegistrarCedula&tipo_persona=${tipo}&cedula=${cedula}`);
                    const data = await response.json();

                    if (data.success && data.data) {
                        mensajePedidoCedula.style.color = 'green';
                        mensajePedidoCedula.innerText = data.source === 'db' ? 'Cliente encontrado en BD.' : 'Cliente registrado desde API.';
                        pedidoNombreCliente.value = data.data.nombre_completo;
                        pedidoIdCliente.value = data.data.id;
                        pedidoNombreCliente.setAttribute('readonly', true);
                    } else {
                        mensajePedidoCedula.style.color = 'orange';
                        mensajePedidoCedula.innerText = 'Cliente no encontrado. Ingrese el nombre para registrarlo.';
                        pedidoNombreCliente.removeAttribute('readonly');
                        pedidoNombreCliente.focus();
                    }
                } catch (error) {
                    console.error('Error al consultar cédula:', error);
                    mensajePedidoCedula.style.color = 'red';
                    mensajePedidoCedula.innerText = 'Error al conectar. Ingrese el nombre manualmente.';
                    pedidoNombreCliente.removeAttribute('readonly');
                    pedidoNombreCliente.focus();
                }
            } else {
                if (mensajePedidoCedula) mensajePedidoCedula.innerText = '';
                if (pedidoNombreCliente) {
                    pedidoNombreCliente.value = '';
                    pedidoNombreCliente.setAttribute('readonly', true);
                }
                if (pedidoIdCliente) pedidoIdCliente.value = '';
            }
        });
    }

    // 2. Dinámica de Detalles
    const container = document.getElementById('detallesContainerNE');
    const btnAdd = document.getElementById('addDetalleNE');
    const spanTotal = document.getElementById('totalNotaEntrega');
    
    // Generar opciones de productos
    let opcionesProductos = '<option value="">-- Seleccionar Producto --</option>';
    if (window.PRODUCTS) {
        window.PRODUCTS.forEach(p => {
            opcionesProductos += `<option value="${p.id}">${p.nombre}</option>`;
        });
    }

    function calcularTotal() {
        let total = 0;
        container.querySelectorAll('.detalle-row').forEach(row => {
            const cant = parseFloat(row.querySelector('.in-cantidad').value) || 0;
            const pre = parseFloat(row.querySelector('.in-precio').value) || 0;
            const sub = cant * pre;
            row.querySelector('.sp-subtotal').innerText = sub.toFixed(2);
            total += sub;
        });
        if (spanTotal) spanTotal.innerText = total.toFixed(2);
    }

    function agregarDetalle() {
        const row = document.createElement('div');
        row.className = 'detalle-row d-flex gap-2 mb-2 align-items-center';
        row.innerHTML = `
            <select class="form-select form-select-sm sel-producto" style="flex: 2;">
                ${opcionesProductos}
            </select>
            <select name="id_variante[]" class="form-select form-select-sm sel-variante" style="flex: 2;" required>
                <option value="">-- Variante --</option>
            </select>
            <input type="number" name="cantidad[]" class="form-control form-control-sm in-cantidad" min="1" value="1" style="flex: 1;" required>
            <input type="number" name="precio_unitario[]" class="form-control form-control-sm in-precio" step="0.01" placeholder="Precio U." style="flex: 1;" required>
            <div style="flex: 1;" class="text-end fw-bold">$<span class="sp-subtotal">0.00</span></div>
            <button type="button" class="btn btn-sm btn-danger btn-remove-detalle">X</button>
        `;

        container.appendChild(row);
        if (typeof initSelect2 === 'function') initSelect2();

        // Eventos
        const selProd = row.querySelector('.sel-producto');
        const selVar = row.querySelector('.sel-variante');
        const inCant = row.querySelector('.in-cantidad');
        const inPre = row.querySelector('.in-precio');
        const btnRem = row.querySelector('.btn-remove-detalle');

        selProd.addEventListener('change', (e) => {
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
            calcularTotal();
        });

        selVar.addEventListener('change', (e) => {
            const selected = selVar.options[selVar.selectedIndex];
            if (selected && selected.dataset.precio) {
                inPre.value = selected.dataset.precio;
                
                if (selected.dataset.stock) {
                    inCant.max = selected.dataset.stock;
                    if (parseInt(inCant.value) > parseInt(selected.dataset.stock)) {
                        inCant.value = selected.dataset.stock;
                    }
                }
                
                calcularTotal();
            }
        });

        inCant.addEventListener('input', () => {
            if (inCant.max && parseInt(inCant.value) > parseInt(inCant.max)) {
                inCant.value = inCant.max;
                alert(`No puedes registrar más del inventario existente. Máximo disponible: ${inCant.max}`);
            }
            calcularTotal();
        });
        inPre.addEventListener('input', calcularTotal);

        btnRem.addEventListener('click', () => {
            row.remove();
            calcularTotal();
        });
    }

    if (btnAdd) {
        btnAdd.addEventListener('click', agregarDetalle);
        // Agregar uno inicial
        agregarDetalle();
    }

    // 3. Ver Detalles por AJAX
    const modalDetalles = document.getElementById('modalDetallesPedido');
    const modalDetallesBody = document.getElementById('modalDetallesPedidoBody');
    const btnVerDetalles = document.querySelectorAll('.btn-ver-detalles');

    btnVerDetalles.forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.getAttribute('data-id');
            modalDetallesBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3">Cargando detalle...</p>
                </div>
            `;
            const bsModal = new bootstrap.Modal(modalDetalles);
            bsModal.show();

            try {
                const response = await fetch(`?c=Notas&accion=detalles_ajax&id=${id}`);
                const data = await response.json();

                if (data.success) {
                    let html = `
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Cliente:</strong> ${data.nota.nombre_cliente} <br>
                                <strong>Fecha:</strong> ${data.nota.fecha_pedido} <br>
                                <strong>Estado:</strong> ${data.nota.estado}
                            </div>
                            <div class="col-sm-6 text-end">
                                <strong>Observaciones:</strong><br>
                                ${data.nota.observaciones || 'N/A'}
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Producto</th>
                                    <th>Variante</th>
                                    <th>Cant.</th>
                                    <th>Precio U.</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    let total = 0;
                    data.detalles.forEach(d => {
                        const sub = d.cantidad * d.precio_unitario;
                        total += sub;
                        html += `
                            <tr>
                                <td>${d.producto_nombre}</td>
                                <td>${d.nombre_variante}</td>
                                <td>${d.cantidad}</td>
                                <td>$${parseFloat(d.precio_unitario).toFixed(2)}</td>
                                <td>$${sub.toFixed(2)}</td>
                            </tr>
                        `;
                    });
                    html += `
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">TOTAL:</th>
                                    <th>$${total.toFixed(2)}</th>
                                </tr>
                            </tfoot>
                        </table>
                    `;
                    modalDetallesBody.innerHTML = html;
                } else {
                    modalDetallesBody.innerHTML = `<div class="alert alert-danger">Error: ${data.error}</div>`;
                }
            } catch (error) {
                console.error(error);
                modalDetallesBody.innerHTML = `<div class="alert alert-danger">Error de conexión al cargar detalles.</div>`;
            }
        });
    });

    // 4. Lógica de Modal de Pagos Múltiples y Cálculo Restante
    function actualizarTotalesPagos(modal) {
        const spanRestanteUsd = modal.querySelector('.restante-usd');
        const spanRestanteBs = modal.querySelector('.restante-bs');
        const btnSubmit = modal.querySelector('button[type="submit"]');
        if (!spanRestanteUsd) return;

        const totalOriginalUsd = parseFloat(spanRestanteUsd.dataset.total) || 0;
        let totalPagandoUsd = 0;
        
        // Asume que la tasa es global de la primera moneda que encuentre
        const selectMonedaFallback = modal.querySelector('.input-moneda-hidden');
        const tasa = selectMonedaFallback ? (parseFloat(selectMonedaFallback.dataset.tasa) || 1) : 1;

        const inputsMonto = modal.querySelectorAll('.input-monto-pago');
        inputsMonto.forEach(input => {
            const container = input.closest('.pago-item');
            const hiddenMoneda = container.querySelector('.input-moneda-hidden');
            const monto = parseFloat(input.value) || 0;
            const moneda = hiddenMoneda ? hiddenMoneda.value : 'USD';
            
            if (moneda === 'USD') {
                totalPagandoUsd += monto;
            } else {
                totalPagandoUsd += (monto / tasa);
            }
        });

        // Tolerancia de precisión
        let restanteUsd = totalOriginalUsd - totalPagandoUsd;
        if (Math.abs(restanteUsd) < 0.01) restanteUsd = 0;

        const mostrarRestanteUsd = Math.max(0, restanteUsd);
        spanRestanteUsd.innerText = '$' + mostrarRestanteUsd.toFixed(2);
        if (spanRestanteBs) {
            spanRestanteBs.innerText = 'Bs ' + (mostrarRestanteUsd * tasa).toFixed(2);
        }
        
        if (btnSubmit) {
            if (restanteUsd !== 0) {
                btnSubmit.disabled = true;
                btnSubmit.classList.add('btn-secondary');
                btnSubmit.classList.remove('btn-success');
                btnSubmit.innerText = 'Monto incompleto';
            } else {
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('btn-secondary');
                btnSubmit.classList.add('btn-success');
                btnSubmit.innerText = 'Guardar Pagos';
            }
        }
    }

    function actualizarOpcionesMetodos(modal) {
        const selects = modal.querySelectorAll('.select-metodo-pago');
        const seleccionados = Array.from(selects).map(s => s.value).filter(val => val !== '');
        
        selects.forEach(select => {
            Array.from(select.options).forEach(option => {
                if (option.value === '') return;
                
                if (seleccionados.includes(option.value) && select.value !== option.value) {
                    option.disabled = true;
                } else {
                    option.disabled = false;
                }
            });
        });
    }

    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-metodo-pago')) {
            const container = e.target.closest('.pago-item');
            const hiddenMoneda = container.querySelector('.input-moneda-hidden');
            const badgeMoneda = container.querySelector('.badge-moneda');
            const nombreMetodo = e.target.options[e.target.selectedIndex].text.toLowerCase();
            
            if (nombreMetodo.includes('bs') || nombreMetodo.includes('pago móvil')) {
                hiddenMoneda.value = 'VES';
                badgeMoneda.innerText = 'Bs';
                badgeMoneda.className = 'badge bg-info badge-moneda';
            } else {
                hiddenMoneda.value = 'USD';
                badgeMoneda.innerText = '$';
                badgeMoneda.className = 'badge bg-success badge-moneda';
            }
            hiddenMoneda.dispatchEvent(new Event('change', { bubbles: true }));
            
            const modal = e.target.closest('.modal');
            if (modal) actualizarOpcionesMetodos(modal);
        }
    });

    document.body.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-monto-pago')) {
            e.target.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

    document.body.addEventListener('focusout', function(e) {
        if (e.target.classList.contains('input-monto-pago')) {
            e.target.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

    document.body.addEventListener('change', function(e) {
        if (e.target.classList.contains('input-monto-pago') || e.target.classList.contains('input-moneda-hidden')) {
            const container = e.target.closest('.pago-item');
            if (!container) return;
            const hiddenMoneda = container.querySelector('.input-moneda-hidden');
            const inputMonto = container.querySelector('.input-monto-pago');
            const spanEquivalente = container.querySelector('.equivalente-pago-text');

            const moneda = hiddenMoneda.value;
            const monto = parseFloat(inputMonto.value) || 0;
            const tasa = parseFloat(hiddenMoneda.dataset.tasa) || 1;

            if (moneda === 'USD') {
                spanEquivalente.innerText = (monto * tasa).toFixed(2) + ' Bs';
            } else {
                spanEquivalente.innerText = '$' + (monto / tasa).toFixed(2);
            }

            const modal = e.target.closest('.modal');
            if (modal) {
                actualizarTotalesPagos(modal);
            }
        }
    });

    const btnsAgregarPago = document.querySelectorAll('.btn-agregar-pago');
    btnsAgregarPago.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const contenedor = document.getElementById('contenedorPagosNE' + id);
            if (!contenedor) return;
            const modal = this.closest('.modal');

            const primerItem = contenedor.querySelector('.pago-item');
            if (primerItem) {
                const nuevoItem = primerItem.cloneNode(true);
                nuevoItem.querySelector('.input-monto-pago').value = '';
                nuevoItem.querySelector('input[name="referencia[]"]').value = '';
                nuevoItem.querySelector('.select-metodo-pago').value = '';
                nuevoItem.querySelector('.equivalente-pago-text').innerText = '$0.00';
                nuevoItem.querySelector('.badge-moneda').innerText = 'Moneda: ?';
                nuevoItem.querySelector('.badge-moneda').className = 'badge bg-secondary badge-moneda';
                nuevoItem.querySelector('.input-moneda-hidden').value = 'USD';
                
                if (!nuevoItem.querySelector('.btn-remove-pago')) {
                    const removeBtnHtml = `<div class="text-end mt-1"><button type="button" class="btn btn-sm btn-danger btn-remove-pago">X</button></div>`;
                    nuevoItem.insertAdjacentHTML('beforeend', removeBtnHtml);
                }
                contenedor.appendChild(nuevoItem);
                if (modal) {
                    actualizarOpcionesMetodos(modal);
                    actualizarTotalesPagos(modal);
                }
            }
        });
    });

    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-pago')) {
            const modal = e.target.closest('.modal');
            e.target.closest('.pago-item').remove();
            if (modal) {
                actualizarOpcionesMetodos(modal);
                actualizarTotalesPagos(modal);
            }
        }
    });
    
    // Iniciar con el boton bloqueado al cargar modales
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', () => {
            actualizarTotalesPagos(modal);
            actualizarOpcionesMetodos(modal);
        });
    });

    // --- Lógica Modalidad Débito/Crédito en Agregar Nota ---
    const switchModalidad = document.getElementById('switchModalidadCredito');
    const labelModalidad = document.getElementById('labelModalidadTexto');
    const inputTipo = document.getElementById('inputTipoModalidad');
    const containerCredito = document.getElementById('opcionesCreditoContainer');
    
    const selPorcentaje = document.getElementById('selectPorcentajeInicial');
    const selCuotas = document.getElementById('selectNroCuotas');
    const proyInicial = document.getElementById('proyeccionInicial');
    const proyCuota = document.getElementById('proyeccionCuota');

    function actualizarProyeccionCreditoYPagos() {
        // Actualizar proyecciones de credito
        const totalNode = document.getElementById('totalNotaEntrega');
        const total = totalNode ? parseFloat(totalNode.innerText) : 0;
        let requeridoUsd = total; // Por defecto es debito
        
        if (switchModalidad && switchModalidad.checked) {
            const pct = parseFloat(selPorcentaje.value) / 100;
            const nroCuotas = parseInt(selCuotas.value) || 1;
            const inicial = total * pct;
            const restante = total - inicial;
            const cuota = restante / nroCuotas;

            if (proyInicial) proyInicial.innerText = inicial.toFixed(2);
            if (proyCuota) proyCuota.innerText = cuota.toFixed(2);
            requeridoUsd = inicial; // Si es credito, solo requerimos la cuota inicial
        }

        // Actualizar UI de requerido
        const reqUsdNode = document.getElementById('montoRequeridoUSD');
        const reqBsNode = document.getElementById('montoRequeridoBS');
        const tasa = window.TASA_ACTUAL || 1;
        
        if (reqUsdNode) reqUsdNode.innerText = '$' + requeridoUsd.toFixed(2);
        if (reqBsNode) reqBsNode.innerText = 'Bs ' + (requeridoUsd * tasa).toFixed(2);
        
        const restanteNode = document.getElementById('restanteInputUSD');
        if (restanteNode) restanteNode.dataset.total = requeridoUsd;
        
        const formModal = document.getElementById('modalAgregarNE');
        if (formModal) actualizarTotalesPagos(formModal);
    }

    if (switchModalidad) {
        switchModalidad.addEventListener('change', function() {
            if (this.checked) {
                if (labelModalidad) {
                    labelModalidad.innerText = 'Crédito';
                    labelModalidad.classList.replace('text-dark', 'text-primary');
                }
                if (inputTipo) inputTipo.value = 'credito';
                if (containerCredito) containerCredito.style.display = 'block';
            } else {
                if (labelModalidad) {
                    labelModalidad.innerText = 'Débito';
                    labelModalidad.classList.replace('text-primary', 'text-dark');
                }
                if (inputTipo) inputTipo.value = 'debito';
                if (containerCredito) containerCredito.style.display = 'none';
            }
            actualizarProyeccionCreditoYPagos();
        });
        
        [selPorcentaje, selCuotas].forEach(el => {
            if (el) el.addEventListener('change', actualizarProyeccionCreditoYPagos);
        });
    }

    // Interceptar la actualización de total de la nueva nota para recalcular
    const observerTotal = new MutationObserver(actualizarProyeccionCreditoYPagos);
    const totalNuevaNotaNode = document.getElementById('totalNotaEntrega');
    if (totalNuevaNotaNode) {
        observerTotal.observe(totalNuevaNotaNode, { childList: true, characterData: true, subtree: true });
    }

    // Boton agregar pago en modal nuevo
    const btnAgregarPagoNuevo = document.querySelector('.btn-agregar-pago-nuevo');
    if (btnAgregarPagoNuevo) {
        btnAgregarPagoNuevo.addEventListener('click', function() {
            const contenedor = document.getElementById('contenedorPagosNENuevo');
            if (!contenedor) return;
            const modal = this.closest('.modal');

            const primerItem = contenedor.querySelector('.pago-item');
            if (primerItem) {
                const nuevoItem = primerItem.cloneNode(true);
                nuevoItem.querySelector('.input-monto-pago').value = '';
                nuevoItem.querySelector('input[name="referencia[]"]').value = '';
                nuevoItem.querySelector('.select-metodo-pago').value = '';
                nuevoItem.querySelector('.equivalente-pago-text').innerText = '$0.00';
                nuevoItem.querySelector('.badge-moneda').innerText = 'Moneda: ?';
                nuevoItem.querySelector('.badge-moneda').className = 'badge bg-secondary badge-moneda';
                nuevoItem.querySelector('.input-moneda-hidden').value = 'USD';
                
                if (!nuevoItem.querySelector('.btn-remove-pago')) {
                    const removeBtnHtml = `<div class="text-end mt-1"><button type="button" class="btn btn-sm btn-danger btn-remove-pago">X</button></div>`;
                    nuevoItem.insertAdjacentHTML('beforeend', removeBtnHtml);
                }
                contenedor.appendChild(nuevoItem);
                if (modal) {
                    actualizarOpcionesMetodos(modal);
                    actualizarTotalesPagos(modal);
                }
            }
        });
    }

});

