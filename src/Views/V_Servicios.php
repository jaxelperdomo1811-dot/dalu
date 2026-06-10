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
    <script src="assets/js/pages/pedidos.js" defer></script>
    <script src="assets/js/pages/servicios.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Servicios</title>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $pedidosC = $pedidosC ?? [];
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
            
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-clientes" data-bs-toggle="tab" data-bs-target="#modulo-clientes" type="button">Pedidos de Clientes</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-almacen" data-bs-toggle="tab" data-bs-target="#modulo-almacen" type="button">Almacén de Pedidos</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Modulo Clientes -->
                <div class="tab-pane fade show active" id="modulo-clientes">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Servicio de Pedidos para Clientes</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPC">+ Nuevo Pedido</button>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaClientes" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nro #</th>
                                    <th scope="col">Nombre Cliente</th>
                                    <th scope="col">Fecha de Registro</th>
                                    <th scope="col">Fecha Estimada de Llegada</th>
                                    <th scope="col">Fecha Real de Llegada</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosC as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                                        <td><?php echo htmlspecialchars($p['nombre_cliente'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_pedido'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_estimada'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_recepcion'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['estado']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $p['id'] ?>">Detalles</button>
                                            <button type="button" class="btn btn-sm btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalAvanzarEstadoCliente<?= $p['id'] ?>" <?= in_array($p['estado'], ['entregado','cancelado']) ? ' disabled' : '' ?>>Siguiente estado</button>
                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarCliente<?= $p['id'] ?>" <?= in_array($p['estado'], ['enviado', 'recibido', 'entregado', 'cancelado']) ? ' disabled title="No se puede cancelar en este estado"' : '' ?>>Cancelar</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modulo Almacen -->
                <div class="tab-pane fade" id="modulo-almacen">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Almacén: Pedidos de Clientes</h1>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaAlmacen" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nro #</th>
                                    <th scope="col">Nombre Cliente</th>
                                    <th scope="col">Fecha Pedido</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidosC as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                                        <td><?php echo htmlspecialchars($p['nombre_cliente'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_pedido'] ?? ''); ?></td>
                                        <td><?php echo htmlspecialchars($p['estado']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $p['id'] ?>">Ver Detalles de Almacén</button>
                                            <!-- Se pueden agregar más acciones exclusivas de almacén aquí -->
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

    <!-- Modales para Clientes -->
    <?php foreach ($pedidosC as $p): 
        $siguienteEstado = $ordenEstadosCliente[$p['estado']] ?? null;
    ?>
        <!-- Confirmar Siguiente Estado Cliente -->
        <?php if ($siguienteEstado): ?>
        <div class="modal fade" id="modalAvanzarEstadoCliente<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=pedidos&accion=avanzarEstado" method="POST">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="from_servicios" value="1">
                        <div class="modal-header">
                            <h5 class="modal-title">Avanzar Estado del Pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas cambiar el estado del pedido nro <?= htmlspecialchars($p['id']) ?> de <strong><?= htmlspecialchars($p['estado']) ?></strong> a <strong class="text-warning"><?= htmlspecialchars($siguienteEstado) ?></strong>?
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

        <!-- Confirmar Eliminación Cliente -->
        <div class="modal fade" id="modalConfirmarEliminarCliente<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=pedidos&accion=cancelarPedido" method="POST">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <input type="hidden" name="from_servicios" value="1">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas cancelar el pedido nro <?= htmlspecialchars($p['id']) ?> de <?= htmlspecialchars($p['nombre_cliente'] ?? 'Cliente') ?>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger">Cancelar Pedido</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- Modal Agregar Pedido Cliente -->
    <div class="modal fade" id="modalAgregarPC" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <form action="?c=servicios&accion=insertCliente" method="POST" enctype="multipart/form-data" class="modal-content" id="formAgregarPC">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Pedido de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="row g-0 h-100">
                        <!-- Lado Izquierdo: Cliente y Detalles -->
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
                                        <input type="text" name="cedula_cliente" class="form-control" id="pedido_cedula_cliente" placeholder="Número de cédula" required>
                                    </div>
                                    <div id="mensaje_pedido_cedula" class="form-text"></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nombre del Cliente</label>
                                    <input type="text" name="nombre_cliente" class="form-control" id="pedido_nombre_cliente" readonly placeholder="Se autocompletará al buscar o ingréselo manualmente...">
                                    <input type="hidden" name="id_cliente" id="pedido_id_cliente">
                                </div>
                            </div>
                            
                            <input type="hidden" name="estado" value="pendiente" />
                            <input type="hidden" name="tipo" value="cliente" />
                            <input type="hidden" name="from_servicios" value="1">
                            
                            <!-- Detalles -->
                            <h5 class="mt-4 border-bottom pb-2">Detalles (Productos del Servicio)</h5>
                            <div id="detallesContainerCliente">
                                <!-- Se poblará por JS -->
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <button type="button" class="btn btn-sm btn-secondary" id="addDetalleCliente">+ Agregar detalle</button>
                                <h4 class="text-end">Costo Total: $<span id="totalPedidoCliente">0.00</span></h4>
                            </div>
                        </div>

                        <!-- Lado Derecho: Pagos y Modalidad -->
                        <div class="col-md-5 p-4 bg-light border-start" style="overflow-y: auto; height: calc(100vh - 130px);">
                            <!-- Configuración de Abono y Modalidad -->
                            <div class="card bg-white shadow-sm mb-3">
                                <div class="card-body py-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label m-0 fw-bold">Abono Inicial Requerido:</label>
                                        <select name="porcentaje_inicial" id="selectPorcentajeInicialPC" class="form-select form-select-sm no-select2" style="width: 100px;">
                                            <option value="40">40%</option>
                                            <option value="60">60%</option>
                                        </select>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label class="form-label m-0 fw-bold">Modo de Pago del Restante:</label>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" id="switchModalidadRestante">
                                            <label class="form-check-label fw-bold ms-2" for="switchModalidadRestante" id="labelModalidadRestanteTexto">De Contado al Entregar</label>
                                        </div>
                                        <input type="hidden" name="tipo_modalidad" id="inputTipoModalidadPC" value="debito">
                                    </div>

                                    <div id="opcionesFinanciamientoContainer" style="display: none;">
                                        <hr class="my-2">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label text-sm m-0">Frecuencia</label>
                                                <select name="frecuencia" id="selectFrecuenciaPC" class="form-select form-select-sm no-select2">
                                                    <option value="semanal">Semanal</option>
                                                    <option value="quincenal">Quincenal</option>
                                                    <option value="mensual">Mensual</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label text-sm m-0">Nro. Cuotas</label>
                                                <select name="nro_cuotas" id="selectNroCuotasPC" class="form-select form-select-sm no-select2">
                                                    <?php for($i=2; $i<=6; $i++): ?>
                                                    <option value="<?= $i ?>"><?= $i ?> Cuotas</option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-end text-sm text-primary">
                                        <em>Proyección: Inicial $<span id="proyeccionInicialPC">0.00</span> | Cuotas $<span id="proyeccionCuotaPC">0.00</span> c/u</em>
                                    </div>
                                </div>
                            </div>

                            <h5 class="border-bottom pb-2">Registro de Pago <small class="text-muted" style="font-size: 0.6em;">Tasa: <?= number_format($tasaActual['valor'] ?? 1, 2) ?> Bs/$</small></h5>
                            
                            <div class="alert alert-info py-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><strong>Monto Adelanto (<span id="textoAdelanto">40%</span>):</strong></span>
                                    <span><span id="montoRequeridoUSDPC" class="fw-bold">$0.00</span> | <span id="montoRequeridoBSPC">Bs 0.00</span></span>
                                </div>
                                <div class="text-end border-top pt-1 mt-1">
                                    <strong>Falta por registrar del Adelanto:</strong> 
                                    <span class="restante-usd text-danger fw-bold" id="restanteInputUSDPC" data-total="0">$0.00</span> | 
                                    <span class="restante-bs text-danger fw-bold" id="restanteInputBSPC">Bs 0.00</span>
                                </div>
                            </div>

                            <div id="contenedorPagosPCNuevo">
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
                            <button type="button" class="btn btn-sm btn-outline-primary w-100 btn-agregar-pago-nuevo-pc">+ Agregar otro método de pago</button>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnGuardarPC" disabled>Guardar Pedido</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detalles del Pedido -->
    <div class="modal fade" id="modalDetallesPedido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetallesPedidoTitulo">Detalles del pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" id="modalDetallesPedidoBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-3">Cargando detalle del pedido...</p>
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
