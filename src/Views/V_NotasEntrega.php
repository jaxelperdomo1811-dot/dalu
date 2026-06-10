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
    <!-- Incluimos nuestro nuevo JS para Notas de Entrega -->
    <script src="assets/js/pages/notas_entrega.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Notas de Entrega</title>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $notas_entrega = $notas_entrega ?? [];
        $clientes = $clientes ?? [];
        $productos = $productos ?? [];
        $tasaActual = $tasaActual ?? ['valor' => 1];
        $metodosPago = $metodosPago ?? [];
        
        $ordenEstadosCliente = [
            'pendiente' => 'confirmado',
            'confirmado' => 'enviado',
            'enviado' => 'recibido',
            'recibido' => 'entregado',
        ];
    ?>
    <script>
        window.PRODUCTS = <?php echo json_encode($productos); ?>;
        window.TASA_ACTUAL = <?php echo json_encode(floatval($tasaActual['valor'] ?? 1)); ?>;
    </script>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="titulo text-black">Notas de Entrega</h1>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarNE">+ Nueva Nota</button>
            </div>

            <ul class="nav nav-tabs mb-3" id="notasTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-debito" data-bs-toggle="tab" data-bs-target="#modulo-debito" type="button">Débito</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-credito" data-bs-toggle="tab" data-bs-target="#modulo-credito" type="button">Crédito</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Modulo Debito -->
                <div class="tab-pane fade show active" id="modulo-debito">
                    <div class="table-responsive">
                        <table id="tablaNotasDebito" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-auto" scope="col">Nro #</th>
                                    <th class="col-auto" scope="col">Cliente</th>
                                    <th class="col-auto" scope="col">Fecha</th>
                                    <th class="col-auto" scope="col">Total</th>
                                    <th class="col-auto" scope="col">Pagado</th>
                                    <th class="col-auto" scope="col">Estado</th>
                                    <th class="col-auto" scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notas_debito ?? [] as $n): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($n['id']); ?></td>
                                        <td><?php echo htmlspecialchars($n['nombre_cliente'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($n['fecha_pedido'] ?? ''); ?></td>
                                        <td>$<?php echo number_format($n['total'], 2); ?></td>
                                        <td>$<?php echo number_format($n['total_pagado'] ?? 0, 2); ?></td>
                                        <td><?php echo htmlspecialchars($n['estado']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $n['id'] ?>">Detalles</button>

                                            <button type="button" class="btn btn-sm btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalAvanzarEstadoNE<?= $n['id'] ?>" <?= in_array($n['estado'], ['entregado','cancelado']) ? ' disabled' : '' ?>>Siguiente estado</button>
                                            
                                            <?php if ($n['puede_despachar']): ?>
                                            <form action="?c=Despacho&accion=insert" method="POST" class="d-inline">
                                                <input type="hidden" name="id_nota_entrega" value="<?= $n['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-primary m-1" onclick="return confirm('¿Crear orden de despacho para esta nota?')">Crear Despacho</button>
                                            </form>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarNE<?= $n['id'] ?>" <?= in_array($n['estado'], ['cancelado']) ? ' disabled' : '' ?>>Cancelar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modulo Credito -->
                <div class="tab-pane fade" id="modulo-credito">
                    <div class="table-responsive">
                        <table id="tablaNotasCredito" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th class="col-auto" scope="col">Nro #</th>
                                    <th class="col-auto" scope="col">Cliente</th>
                                    <th class="col-auto" scope="col">Fecha</th>
                                    <th class="col-auto" scope="col">Total</th>
                                    <th class="col-auto" scope="col">Pagado</th>
                                    <th class="col-auto" scope="col">Estado</th>
                                    <th class="col-auto" scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notas_credito ?? [] as $n): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($n['id']); ?></td>
                                        <td><?php echo htmlspecialchars($n['nombre_cliente'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($n['fecha_pedido'] ?? ''); ?></td>
                                        <td>$<?php echo number_format($n['total'], 2); ?></td>
                                        <td>$<?php echo number_format($n['total_pagado'] ?? 0, 2); ?></td>
                                        <td><?php echo htmlspecialchars($n['estado']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $n['id'] ?>">Detalles</button>

                                            <button type="button" class="btn btn-sm btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalAvanzarEstadoNE<?= $n['id'] ?>" <?= in_array($n['estado'], ['entregado','cancelado']) ? ' disabled' : '' ?>>Siguiente estado</button>
                                            
                                            <?php if ($n['puede_despachar']): ?>
                                            <form action="?c=Despacho&accion=insert" method="POST" class="d-inline">
                                                <input type="hidden" name="id_nota_entrega" value="<?= $n['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-primary m-1" onclick="return confirm('¿Crear orden de despacho para esta nota?')">Crear Despacho</button>
                                            </form>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarNE<?= $n['id'] ?>" <?= in_array($n['estado'], ['cancelado']) ? ' disabled' : '' ?>>Cancelar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modales para Notas -->
    <?php foreach ($notas_entrega as $n): 
        $siguienteEstado = $ordenEstadosCliente[$n['estado']] ?? null;
    ?>
        <!-- Confirmar Siguiente Estado -->
        <?php if ($siguienteEstado): ?>
        <div class="modal fade" id="modalAvanzarEstadoNE<?= $n['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=Notas&accion=avanzarEstado" method="POST">
                        <input type="hidden" name="id" value="<?= $n['id'] ?>">
                        <input type="hidden" name="nuevo_estado" value="<?= $siguienteEstado ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Avanzar Estado</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas cambiar el estado de la nota nro <?= htmlspecialchars($n['id']) ?> de <strong><?= htmlspecialchars($n['estado']) ?></strong> a <strong class="text-warning"><?= htmlspecialchars($siguienteEstado) ?></strong>?
                            <?php if ($siguienteEstado === 'confirmado'): ?>
                                <br><small class="text-danger">Avanzar a confirmado descontará el inventario automáticamente.</small>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-warning">Avanzar a <?= htmlspecialchars($siguienteEstado) ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Confirmar Eliminación -->
        <div class="modal fade" id="modalConfirmarEliminarNE<?= $n['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=Notas&accion=cancelar" method="POST">
                        <input type="hidden" name="id" value="<?= $n['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar Nota</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas cancelar la nota de entrega nro <?= htmlspecialchars($n['id']) ?> de <?= htmlspecialchars($n['nombre_cliente'] ?? 'Cliente') ?>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger">Cancelar Nota</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    <?php endforeach; ?>

    <!-- Modal Agregar Nota de Entrega -->
    <div class="modal fade" id="modalAgregarNE" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <form action="?c=Notas&accion=insert" method="POST" class="modal-content" id="formAgregarNE">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nota de Entrega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0 h-100">
                        <!-- Lado Izquierdo: Cliente y Productos -->
                        <div class="col-md-7 p-4" style="overflow-y: auto; height: calc(100vh - 130px);">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Cédula del Cliente</label>
                                    <div class="input-group">
                                        <select name="tipo_persona" class="form-select no-select2" id="pedido_tipo_persona" style="max-width: 80px;">
                                            <option value="V-">V-</option>
                                            <option value="E-">E-</option>
                                            <option value="J-">J-</option>
                                        </select>
                                        <input type="text" name="cedula_cliente" class="form-control" id="pedido_cedula_cliente" placeholder="Número" required>
                                    </div>
                                    <div id="mensaje_pedido_cedula" class="form-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nombre del Cliente</label>
                                    <input type="text" name="nombre_cliente" class="form-control" id="pedido_nombre_cliente" readonly placeholder="Ingrese para buscar o agregar...">
                                    <input type="hidden" name="id_cliente" id="pedido_id_cliente">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="2"></textarea>
                            </div>

                            <input type="hidden" name="estado" value="pendiente" />

                            <!-- Detalles -->
                            <h5 class="mt-4 border-bottom pb-2">Detalles (Productos)</h5>
                            <div id="detallesContainerNE">
                                <!-- Se poblará por JS -->
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-sm btn-secondary" id="addDetalleNE">+ Agregar producto</button>
                                <h4 class="text-end">Total Nota: $<span id="totalNotaEntrega">0.00</span></h4>
                            </div>
                        </div>
                        
                        <!-- Lado Derecho: Pagos y Modalidad -->
                        <div class="col-md-5 p-4 bg-light border-start" style="overflow-y: auto; height: calc(100vh - 130px);">
                            <!-- Configuración de Modalidad -->
                            <div class="card bg-white shadow-sm mb-3">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label m-0 fw-bold">Modalidad de Pago:</label>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" id="switchModalidadCredito">
                                            <label class="form-check-label fw-bold ms-2" for="switchModalidadCredito" id="labelModalidadTexto">Débito</label>
                                        </div>
                                        <input type="hidden" name="tipo" id="inputTipoModalidad" value="debito">
                                    </div>

                                    <div id="opcionesCreditoContainer" style="display: none;">
                                        <hr class="my-2">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="form-label text-sm m-0">Cuota Inicial (%)</label>
                                                <select name="porcentaje_inicial" id="selectPorcentajeInicial" class="form-select form-select-sm">
                                                    <option value="40">40%</option>
                                                    <option value="60">60%</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm m-0">Frecuencia</label>
                                                <select name="frecuencia" id="selectFrecuencia" class="form-select form-select-sm">
                                                    <option value="semanal">Semanal</option>
                                                    <option value="quincenal">Quincenal</option>
                                                    <option value="mensual">Mensual</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-sm m-0">Nro. Cuotas</label>
                                                <select name="nro_cuotas" id="selectNroCuotas" class="form-select form-select-sm">
                                                    <?php for($i=1; $i<=6; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?> Cuota<?= $i>1?'s':'' ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-end text-sm text-primary">
                                            <em>Proyección: Inicial $<span id="proyeccionInicial">0.00</span> | Cuotas $<span id="proyeccionCuota">0.00</span> c/u</em>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2">Registro de Pago <small class="text-muted" style="font-size: 0.6em;">Tasa: <?= number_format($tasaActual['valor'] ?? 1, 2) ?> Bs/$</small></h5>
                            
                            <div class="alert alert-info py-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>Monto a pagar ahora:</strong></span>
                                    <span><span id="montoRequeridoUSD" class="fw-bold">$0.00</span> | <span id="montoRequeridoBS">Bs 0.00</span></span>
                                </div>
                                <div class="text-end border-top pt-1 mt-1">
                                    <strong>Falta por registrar:</strong> 
                                    <span class="restante-usd text-danger fw-bold" id="restanteInputUSD" data-total="0">$0.00</span> | 
                                    <span class="restante-bs text-danger fw-bold" id="restanteInputBS">Bs 0.00</span>
                                </div>
                            </div>

                            <div id="contenedorPagosNENuevo">
                                <div class="pago-item border p-2 mb-2 rounded bg-white shadow-sm">
                                    <div class="row mb-2">
                                        <div class="col-7">
                                            <label class="form-label text-sm m-0">Método de Pago</label>
                                            <select name="id_metodo_pago[]" class="form-select form-select-sm select-metodo-pago no-select2" required>
                                                <option value="" disabled selected>Seleccione...</option>
                                                <?php foreach ($metodosPago as $metodo): ?>
                                                    <option value="<?= $metodo['id'] ?>"><?= htmlspecialchars($metodo['nombre']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-5 text-end pt-3">
                                            <span class="badge bg-secondary badge-moneda">Moneda: ?</span>
                                            <input type="hidden" name="moneda[]" class="input-moneda-hidden" value="USD" data-tasa="<?= $tasaActual['valor'] ?? 1 ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <label class="form-label text-sm m-0">Monto</label>
                                            <input type="number" step="0.01" name="monto_ingresado[]" class="form-control form-control-sm input-monto-pago" required>
                                        </div>
                                        <div class="col-6 pt-4 text-end">
                                            <label class="form-label text-muted text-sm m-0">Equiv: <span class="equivalente-pago-text text-dark fw-bold">$0.00</span></label>
                                        </div>
                                    </div>
                                    <div>
                                        <input type="text" name="referencia[]" class="form-control form-control-sm" placeholder="Ref/Comprobante">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 btn-agregar-pago-nuevo">+ Agregar otro método de pago</button>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarNotaPagos" disabled>Guardar Nota y Pagos</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detalles del Pedido -->
    <div class="modal fade" id="modalDetallesPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesPedidoTitulo">Detalles de la Nota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalDetallesPedidoBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3">Cargando detalle...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
