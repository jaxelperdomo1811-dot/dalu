<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Administración de Créditos</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $creditos = $creditos ?? [];
    ?>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="titulo text-black">Créditos Otorgados</h1>
            </div>

            <div class="table-responsive">
                <table id="tablaCreditos" class="table-DT table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nota #</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha Pedido</th>
                            <th scope="col">Monto Inicial</th>
                            <th scope="col">Cuotas</th>
                            <th scope="col">Monto x Cuota</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($creditos as $c): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($c['nota_id']); ?></td>
                                <td><?php echo htmlspecialchars($c['cliente_nombre'] ?? 'Desconocido'); ?></td>
                                <td><?php echo htmlspecialchars($c['fecha_pedido']); ?></td>
                                <td>$<?php echo number_format($c['monto_cuota_inicial'], 2); ?> (<?php echo $c['porcentaje_inicial']; ?>%)</td>
                                <td><?php echo htmlspecialchars($c['nro_cuotas']); ?> (<?php echo htmlspecialchars($c['frecuencia']); ?>)</td>
                                <td>$<?php echo number_format($c['monto_por_cuota'], 2); ?></td>
                                <td>
                                    <?php if ($c['estado'] === 'pagado'): ?>
                                        <span class="badge bg-success">Pagado</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info m-1" data-bs-toggle="modal" data-bs-target="#modalDetallesCredito<?= $c['id'] ?>">Ver Cuotas</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modales de Detalles de Cuotas -->
    <?php foreach ($creditos as $c): ?>
    <div class="modal fade" id="modalDetallesCredito<?= $c['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cuotas del Crédito - Nota #<?= $c['nota_id'] ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Monto Total</th>
                                    <th>Monto Pendiente</th>
                                    <th>Vencimiento</th>
                                    <th>Fecha Pago</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($c['cuotas'])): ?>
                                    <?php 
                                        $monto_total_pendiente = 0;
                                        $cuota_proxima = null;
                                    ?>
                                    <?php foreach ($c['cuotas'] as $cuota): ?>
                                    <?php 
                                        if ($cuota['estado'] !== 'pagado') {
                                            $monto_total_pendiente += $cuota['monto_restante'];
                                            if ($cuota_proxima === null) {
                                                $cuota_proxima = $cuota['monto_restante'];
                                            }
                                        }
                                    ?>
                                    <tr>
                                        <td><?= $cuota['nro_cuota'] ?></td>
                                        <td><?= ucfirst($cuota['tipo_cuota']) ?></td>
                                        <td>$<?= number_format($cuota['monto'], 2) ?></td>
                                        <td>$<?= number_format($cuota['monto_restante'], 2) ?></td>
                                        <td><?= $cuota['fecha_vencimiento'] ?></td>
                                        <td><?= $cuota['fecha_pago'] ?? '-' ?></td>
                                        <td>
                                            <?php if ($cuota['estado'] === 'pagado'): ?>
                                                <span class="badge bg-success">Pagado</span>
                                            <?php elseif ($cuota['estado'] === 'retrasado'): ?>
                                                <span class="badge bg-danger">Retrasado</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pendiente</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="text-center">No hay cuotas registradas.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <?php if ($c['estado'] !== 'pagado'): ?>
                        <button type="button" class="btn btn-primary btn-abrir-wizard" 
                            data-bs-dismiss="modal"
                            data-id-nota="<?= $c['id_nota_entrega'] ?>"
                            data-cuota-prox="<?= $cuota_proxima ?? 0 ?>"
                            data-total-pend="<?= $monto_total_pendiente ?>"
                            data-tasa="<?= $tasaActual['valor'] ?? 1 ?>"
                            data-cliente="<?= htmlspecialchars($c['cliente_nombre'] ?? 'Desconocido') ?>">
                            Abonar / Pagar
                        </button>
                    <?php else: ?>
                        <span class="text-success fw-bold">✓ Crédito Solventado</span>
                    <?php endif; ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- WIZARD DE PAGO (ESTILO CASHEA) -->
    <div class="modal fade" id="modalWizardPago" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 1rem;">
                <form action="?c=Pagos&accion=insert" method="POST" id="formWizardPago">
                    <input type="hidden" name="id_nota_entrega" id="wizard_id_nota">
                    
                    <!-- PASO 1: SELECCION DE MONTO -->
                    <div id="wizardStep1" class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <h4 class="fw-bold m-0 text-dark">¿Cuánto quieres pagar hoy?</h4>
                        </div>
                        
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-light rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <span class="fs-4">🏪</span>
                            </div>
                            <div>
                                <h6 class="m-0 fw-bold" id="wizard_cliente_nombre">Cliente</h6>
                                <small class="text-muted">Orden de Crédito</small>
                            </div>
                        </div>

                        <div class="d-grid gap-3">
                            <div class="card shadow-sm border-0 wizard-option" data-type="proxima" style="cursor: pointer; border-radius: 12px;">
                                <div class="card-body d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="m-0 fw-bold">1 cuota x $<span id="lbl_cuota_proxima">0.00</span></h6>
                                        <small class="text-muted">La más próxima a vencer</small>
                                    </div>
                                    <span class="text-primary fs-4">›</span>
                                </div>
                            </div>
                            
                            <div class="card shadow-sm border-0 wizard-option" data-type="todo" style="cursor: pointer; border-radius: 12px;">
                                <div class="card-body d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="m-0 fw-bold">Todas las cuotas x $<span id="lbl_cuota_todo">0.00</span></h6>
                                        <small class="text-muted">Adelanta y solventa</small>
                                    </div>
                                    <span class="text-primary fs-4">›</span>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0 wizard-option" data-type="otro" style="cursor: pointer; border-radius: 12px;">
                                <div class="card-body d-flex justify-content-between align-items-center py-3">
                                    <div>
                                        <h6 class="m-0 fw-bold">Otro monto</h6>
                                    </div>
                                    <span class="text-primary fs-4">›</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 text-center d-none" id="divInputOtroMonto">
                            <label class="text-muted small">Ingresa el monto a abonar (Máx $<span id="lbl_max_monto"></span>)</label>
                            <div class="input-group input-group-lg mt-2 mb-3">
                                <span class="input-group-text bg-white border-end-0 fw-bold">$</span>
                                <input type="number" class="form-control border-start-0 fs-2 fw-bold text-center" id="inputOtroMonto" step="0.01" min="0.01">
                            </div>
                            <button type="button" class="btn btn-dark btn-lg w-100" id="btnConfirmarMontoLibre" style="border-radius: 12px;">Confirmar monto</button>
                        </div>
                    </div>

                    <!-- PASO 2: METODO DE PAGO -->
                    <div id="wizardStep2" class="p-4 d-none">
                        <div class="d-flex align-items-center mb-4">
                            <button type="button" class="btn btn-link text-dark p-0 me-3 text-decoration-none fs-5" id="btnBackStep1">‹</button>
                            <h4 class="fw-bold m-0 text-dark">¿Cómo vas a pagar?</h4>
                        </div>
                        
                        <div class="alert alert-info text-center" style="border-radius: 12px;">
                            Total a pagar: <strong class="fs-4">$<span id="wizard_monto_final_usd">0.00</span></strong>
                            <br>
                            <small>Equivalente: Bs <span id="wizard_monto_final_bs">0.00</span> (Tasa: <?= $tasaActual['valor'] ?? 1 ?>)</small>
                        </div>

                        <!-- Campos ocultos requeridos por CPagos -->
                        <input type="hidden" name="monto_ingresado[]" id="input_monto_enviar">
                        <input type="hidden" name="moneda[]" id="input_moneda_enviar" value="USD">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Método de pago</label>
                            <select name="id_metodo_pago[]" class="form-select form-select-lg" id="wizard_metodo" required style="border-radius: 10px;">
                                <option value="" disabled selected>Seleccione...</option>
                                <?php if(isset($metodosPago)): ?>
                                    <?php foreach ($metodosPago as $metodo): ?>
                                        <option value="<?= $metodo['id'] ?>"><?= htmlspecialchars($metodo['nombre']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Referencia / Comprobante</label>
                            <input type="text" name="referencia[]" class="form-control form-control-lg" placeholder="Ej: 12345678" required style="border-radius: 10px;">
                        </div>

                        <button type="submit" class="btn btn-dark btn-lg w-100" style="border-radius: 12px;">Ya pagué</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Logica del Wizard de Pagos en V_Creditos
        document.addEventListener('DOMContentLoaded', () => {
            const modalWizard = new bootstrap.Modal(document.getElementById('modalWizardPago'));
            const step1 = document.getElementById('wizardStep1');
            const step2 = document.getElementById('wizardStep2');
            
            const btnBack = document.getElementById('btnBackStep1');
            const options = document.querySelectorAll('.wizard-option');
            const divOtro = document.getElementById('divInputOtroMonto');
            const inputOtro = document.getElementById('inputOtroMonto');
            const btnConfirmarLibre = document.getElementById('btnConfirmarMontoLibre');

            let montoSeleccionadoUsd = 0;
            let maxMontoUsd = 0;
            let tasaDia = 1;

            // Al abrir el wizard desde cualquier boton "Abonar"
            document.querySelectorAll('.btn-abrir-wizard').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const idNota = btn.getAttribute('data-id-nota');
                    const cProx = parseFloat(btn.getAttribute('data-cuota-prox')) || 0;
                    const cTodo = parseFloat(btn.getAttribute('data-total-pend')) || 0;
                    tasaDia = parseFloat(btn.getAttribute('data-tasa')) || 1;
                    const cliente = btn.getAttribute('data-cliente');

                    document.getElementById('wizard_id_nota').value = idNota;
                    document.getElementById('wizard_cliente_nombre').innerText = cliente;
                    document.getElementById('lbl_cuota_proxima').innerText = cProx.toFixed(2);
                    document.getElementById('lbl_cuota_todo').innerText = cTodo.toFixed(2);
                    document.getElementById('lbl_max_monto').innerText = cTodo.toFixed(2);
                    
                    maxMontoUsd = cTodo;
                    
                    // Reset UI
                    step1.classList.remove('d-none');
                    step2.classList.add('d-none');
                    divOtro.classList.add('d-none');
                    inputOtro.value = '';

                    modalWizard.show();
                });
            });

            // Seleccion de opciones en Paso 1
            options.forEach(opt => {
                opt.addEventListener('click', () => {
                    const type = opt.getAttribute('data-type');
                    
                    if (type === 'proxima') {
                        montoSeleccionadoUsd = parseFloat(document.getElementById('lbl_cuota_proxima').innerText);
                        avanzarPaso2();
                    } else if (type === 'todo') {
                        montoSeleccionadoUsd = maxMontoUsd;
                        avanzarPaso2();
                    } else if (type === 'otro') {
                        divOtro.classList.remove('d-none');
                        inputOtro.focus();
                    }
                });
            });

            btnConfirmarLibre.addEventListener('click', () => {
                const val = parseFloat(inputOtro.value) || 0;
                if (val <= 0) {
                    alert("Ingrese un monto válido.");
                    return;
                }
                if (val > maxMontoUsd) {
                    alert("El monto no puede superar la deuda total de $" + maxMontoUsd.toFixed(2));
                    return;
                }
                montoSeleccionadoUsd = val;
                avanzarPaso2();
            });

            function avanzarPaso2() {
                step1.classList.add('d-none');
                step2.classList.remove('d-none');

                document.getElementById('wizard_monto_final_usd').innerText = montoSeleccionadoUsd.toFixed(2);
                document.getElementById('wizard_monto_final_bs').innerText = (montoSeleccionadoUsd * tasaDia).toFixed(2);
                
                // Set hidden value
                document.getElementById('input_monto_enviar').value = montoSeleccionadoUsd;
            }

            btnBack.addEventListener('click', () => {
                step2.classList.add('d-none');
                step1.classList.remove('d-none');
            });

            // Detectar moneda según el método seleccionado
            document.getElementById('wizard_metodo').addEventListener('change', function() {
                const nombreMetodo = this.options[this.selectedIndex].text.toLowerCase();
                const inputMoneda = document.getElementById('input_moneda_enviar');
                const inputMonto = document.getElementById('input_monto_enviar');
                
                if (nombreMetodo.includes('bs') || nombreMetodo.includes('pago móvil')) {
                    inputMoneda.value = 'VES';
                    inputMonto.value = (montoSeleccionadoUsd * tasaDia).toFixed(2);
                } else {
                    inputMoneda.value = 'USD';
                    inputMonto.value = montoSeleccionadoUsd.toFixed(2);
                }
            });
        });
    </script>
</body>
</html>
