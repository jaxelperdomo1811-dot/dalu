document.addEventListener('DOMContentLoaded', () => {
    // Lógica para buscar/registrar cliente por cédula en el modal de pedidos
    const pedidoCedula = document.getElementById('pedido_cedula_cliente');
    const pedidoTipoPersona = document.getElementById('pedido_tipo_persona');
    const mensajePedidoCedula = document.getElementById('mensaje_pedido_cedula');
    const pedidoNombreCliente = document.getElementById('pedido_nombre_cliente');
    const pedidoIdCliente = document.getElementById('pedido_id_cliente');

    if (pedidoCedula && pedidoTipoPersona) {
        pedidoCedula.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 9);
        });
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

    // ====== Lógica de Pagos, Modalidad y Cálculo de Totales para Pedidos Cliente ======
    const formPC = document.getElementById('formAgregarPC');
    if (!formPC) return;

    const detallesContainer = document.getElementById('detallesContainerCliente');
    const totalPedidoSpan = document.getElementById('totalPedidoCliente');
    
    // Modalidad y proyecciones
    const switchModalidadRestante = document.getElementById('switchModalidadRestante');
    const labelModalidadRestanteTexto = document.getElementById('labelModalidadRestanteTexto');
    const inputTipoModalidadPC = document.getElementById('inputTipoModalidadPC');
    const opcionesFinanciamientoContainer = document.getElementById('opcionesFinanciamientoContainer');
    const selectPorcentajeInicialPC = document.getElementById('selectPorcentajeInicialPC');
    const selectNroCuotasPC = document.getElementById('selectNroCuotasPC');
    const proyeccionInicialPC = document.getElementById('proyeccionInicialPC');
    const proyeccionCuotaPC = document.getElementById('proyeccionCuotaPC');
    const textoAdelanto = document.getElementById('textoAdelanto');
    
    // Pagos
    const montoRequeridoUSDPC = document.getElementById('montoRequeridoUSDPC');
    const montoRequeridoBSPC = document.getElementById('montoRequeridoBSPC');
    const restanteInputUSDPC = document.getElementById('restanteInputUSDPC');
    const restanteInputBSPC = document.getElementById('restanteInputBSPC');
    const btnGuardarPC = document.getElementById('btnGuardarPC');
    const btnAgregarPagoNuevoPC = document.querySelector('.btn-agregar-pago-nuevo-pc');
    const contenedorPagosPCNuevo = document.getElementById('contenedorPagosPCNuevo');
    
    const TASA_ACTUAL = window.TASA_ACTUAL || 1;

    // Calcular Totales del Pedido
    function calcularTotales() {
        let total = 0;
        const rows = detallesContainer.querySelectorAll('.detalle-row');
        rows.forEach(row => {
            const cantInput = row.querySelector('.input-cantidad-detalle');
            const precioInput = row.querySelector('.input-precio-detalle');
            if (cantInput && precioInput) {
                const cant = parseInt(cantInput.value) || 0;
                const precio = parseFloat(precioInput.value) || 0;
                total += (cant * precio);
            }
        });
        
        totalPedidoSpan.innerText = total.toFixed(2);
        actualizarProyeccionYPagos(total);
    }

    // Escuchar cambios en detalles (delegación de eventos)
    detallesContainer.addEventListener('input', (e) => {
        if (e.target.classList.contains('input-cantidad-detalle') || e.target.classList.contains('input-precio-detalle')) {
            calcularTotales();
        }
    });

    detallesContainer.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-remove-detalle')) {
            // El click handler original elimina la fila, así que esperamos un microsegundo para recalcular
            setTimeout(calcularTotales, 50);
        }
    });

    // Toggle Modalidad Restante (Contado vs Financiar)
    switchModalidadRestante.addEventListener('change', function() {
        if (this.checked) {
            labelModalidadRestanteTexto.innerText = 'Financiar Restante';
            inputTipoModalidadPC.value = 'credito';
            opcionesFinanciamientoContainer.style.display = 'block';
        } else {
            labelModalidadRestanteTexto.innerText = 'De Contado al Entregar';
            inputTipoModalidadPC.value = 'debito';
            opcionesFinanciamientoContainer.style.display = 'none';
        }
        calcularTotales();
    });

    // Cambios en % o Cuotas
    selectPorcentajeInicialPC.addEventListener('change', calcularTotales);
    selectNroCuotasPC.addEventListener('change', calcularTotales);

    function actualizarProyeccionYPagos(totalPedido) {
        const porcentaje = parseFloat(selectPorcentajeInicialPC.value) || 40;
        textoAdelanto.innerText = porcentaje + '%';
        
        const inicial = totalPedido * (porcentaje / 100);
        const restante = totalPedido - inicial;
        
        // Calcular cuotas si es crédito
        let cuota = 0;
        if (inputTipoModalidadPC.value === 'credito') {
            const nroCuotas = parseInt(selectNroCuotasPC.value) || 1;
            cuota = (nroCuotas > 0) ? (restante / nroCuotas) : 0;
        }

        proyeccionInicialPC.innerText = inicial.toFixed(2);
        proyeccionCuotaPC.innerText = cuota.toFixed(2);

        // Actualizar requeridos
        montoRequeridoUSDPC.innerText = '$' + inicial.toFixed(2);
        montoRequeridoBSPC.innerText = 'Bs ' + (inicial * TASA_ACTUAL).toFixed(2);

        validarPagos();
    }

    // Validar pagos ingresados vs Adelanto requerido
    function validarPagos() {
        const totalAdelantoStr = proyeccionInicialPC.innerText;
        const totalAdelanto = parseFloat(totalAdelantoStr) || 0;
        
        let sumUSD = 0;
        const items = contenedorPagosPCNuevo.querySelectorAll('.pago-item');
        items.forEach(item => {
            const selectMetodo = item.querySelector('.select-metodo-pago');
            const hiddenMoneda = item.querySelector('.input-moneda-hidden');
            const inputMonto = item.querySelector('.input-monto-pago');
            
            if (selectMetodo && selectMetodo.value && inputMonto) {
                const monto = parseFloat(inputMonto.value) || 0;
                const moneda = hiddenMoneda.value;
                if (moneda === 'USD') {
                    sumUSD += monto;
                } else {
                    sumUSD += (monto / TASA_ACTUAL);
                }
            }
        });

        const falta = totalAdelanto - sumUSD;

        if (falta <= 0.01) { // Tolerancia decimal
            restanteInputUSDPC.innerText = '$0.00';
            restanteInputBSPC.innerText = 'Bs 0.00';
            restanteInputUSDPC.classList.replace('text-danger', 'text-success');
            restanteInputBSPC.classList.replace('text-danger', 'text-success');
            if (totalAdelanto > 0) {
                btnGuardarPC.disabled = false;
            } else {
                btnGuardarPC.disabled = (parseFloat(totalPedidoSpan.innerText) === 0);
            }
        } else {
            restanteInputUSDPC.innerText = '$' + falta.toFixed(2);
            restanteInputBSPC.innerText = 'Bs ' + (falta * TASA_ACTUAL).toFixed(2);
            restanteInputUSDPC.classList.replace('text-success', 'text-danger');
            restanteInputBSPC.classList.replace('text-success', 'text-danger');
            btnGuardarPC.disabled = true;
        }
    }

    // Eventos de Pagos
    contenedorPagosPCNuevo.addEventListener('change', function(e) {
        if (e.target.classList.contains('select-metodo-pago')) {
            const container = e.target.closest('.pago-item');
            const hiddenMoneda = container.querySelector('.input-moneda-hidden');
            const badgeMoneda = container.querySelector('.badge-moneda');
            const inputReferencia = container.querySelector('input[name="referencia[]"]');
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

            if (inputReferencia) {
                if (nombreMetodo.includes('efectivo')) {
                    inputReferencia.style.display = 'none';
                    inputReferencia.required = false;
                    inputReferencia.value = '';
                } else {
                    inputReferencia.style.display = 'block';
                    inputReferencia.required = true;
                }
            }
        }
        
        if (e.target.classList.contains('input-monto-pago') || e.target.classList.contains('input-moneda-hidden')) {
            const container = e.target.closest('.pago-item');
            if (!container) return;
            const hiddenMoneda = container.querySelector('.input-moneda-hidden');
            const inputMonto = container.querySelector('.input-monto-pago');
            const spanEquivalente = container.querySelector('.equivalente-pago-text');

            const moneda = hiddenMoneda.value;
            const monto = parseFloat(inputMonto.value) || 0;

            if (moneda === 'USD') {
                spanEquivalente.innerText = (monto * TASA_ACTUAL).toFixed(2) + ' Bs';
            } else {
                spanEquivalente.innerText = '$' + (monto / TASA_ACTUAL).toFixed(2);
            }
            validarPagos();
        }
    });

    contenedorPagosPCNuevo.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-monto-pago')) {
            e.target.dispatchEvent(new Event('change', { bubbles: true }));
        }
    });

    btnAgregarPagoNuevoPC.addEventListener('click', function() {
        const primerItem = contenedorPagosPCNuevo.querySelector('.pago-item');
        if (primerItem) {
            const nuevoItem = primerItem.cloneNode(true);
            nuevoItem.querySelector('.input-monto-pago').value = '';
            
            const inputReferenciaNuevo = nuevoItem.querySelector('input[name="referencia[]"]');
            if (inputReferenciaNuevo) {
                inputReferenciaNuevo.value = '';
                inputReferenciaNuevo.style.display = 'block';
                inputReferenciaNuevo.required = false;
            }
            
            nuevoItem.querySelector('.select-metodo-pago').value = '';
            nuevoItem.querySelector('.equivalente-pago-text').innerText = '$0.00';
            nuevoItem.querySelector('.badge-moneda').innerText = 'Moneda: ?';
            nuevoItem.querySelector('.badge-moneda').className = 'badge bg-secondary badge-moneda';
            nuevoItem.querySelector('.input-moneda-hidden').value = 'USD';
            
            if (!nuevoItem.querySelector('.btn-remove-pago')) {
                const removeBtnHtml = `<div class="text-end mt-1"><button type="button" class="btn btn-sm btn-danger btn-remove-pago">X</button></div>`;
                nuevoItem.insertAdjacentHTML('beforeend', removeBtnHtml);
            }
            contenedorPagosPCNuevo.appendChild(nuevoItem);
            
            if (typeof initSelect2 === 'function') initSelect2();
            validarPagos();
        }
    });

    contenedorPagosPCNuevo.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-pago')) {
            const item = e.target.closest('.pago-item');
            if (item) {
                item.remove();
                validarPagos();
            }
        }
    });

    // Llamar un cálculo inicial
    setTimeout(calcularTotales, 500);

});
