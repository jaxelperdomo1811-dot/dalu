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
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Pedidos</title>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $pedidos = $pedidos ?? [];
        $pedidosC = $pedidosC ?? [];
        $pedidosP = $pedidosP ?? [];
        $clientes = $clientes ?? [];
        $proveedores = $proveedores ?? [];
        $productos = $productos ?? [];
        
        $ordenEstadosTienda = [
            'pendiente' => 'confirmado',
            'confirmado' => 'enviado',
            'enviado' => 'recibido',
        ];

        $ordenEstadosCliente = [
            'pendiente' => 'confirmado',
            'confirmado' => 'enviado',
            'enviado' => 'recibido',
            'recibido' => 'entregado',
        ];
    ?>
    <script>
        window.PRODUCTS = <?php echo json_encode($productos); ?>;
    </script>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-tienda" data-bs-toggle="tab" data-bs-target="#modulo-tienda" type="button">Tienda</button>
                </li>
                
            </ul>

            <div class="tab-content">
                <!-- Modulo Tienda -->
                <div class="tab-pane fade show active" id="modulo-tienda">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Pedidos de la Tienda</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarPT">+ Nuevo Pedido</button>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaTienda" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Nro #</th>
                                    <th scope="col">Nombre Proveedor</th>
                                    <th scope="col">Fecha de Registro</th>
                                    <th scope="col">Fecha Estimada de Llegada</th>
                                    <th scope="col">Fecha Real de Llegada</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pedidos as $p): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($p['id']); ?></td>
                                        <td><?php echo htmlspecialchars($p['nombre_proveedor']); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_registro']); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_estimada'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_recepcion'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($p['estado']); ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $p['id'] ?>">Detalles</button>
                                            <button type="button" class="btn btn-sm btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalAvanzarEstadoTienda<?= $p['id'] ?>" <?= in_array($p['estado'], ['recibido','cancelado']) ? ' disabled' : '' ?>>Siguiente estado</button>
                                            <?php if ($p['estado'] === 'recibido'): ?>
                                                <a href="?c=entradas&accion=crearDesdePedido&pedido_id=<?= $p['id'] ?>" class="btn btn-sm btn-success m-1">Crear Entrada</a>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-sm btn-success m-1" disabled title="El pedido debe estar en estado 'recibido'">Crear Entrada</button>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarTienda<?= $p['id'] ?>" <?= in_array($p['estado'], ['enviado', 'recibido', 'entregado', 'cancelado']) ? ' disabled title="No se puede cancelar en este estado"' : '' ?>>Cancelar</button>
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

    <!-- Modales para Tienda -->
    <?php foreach ($pedidos as $p): 
        $siguienteEstado = $ordenEstadosTienda[$p['estado']] ?? null;
    ?>
        <!-- Confirmar Siguiente Estado Tienda -->
        <?php if ($siguienteEstado): ?>
        <div class="modal fade" id="modalAvanzarEstadoTienda<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=pedidos&accion=avanzarEstado" method="POST">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

        <!-- Confirmar Eliminación Tienda -->
        <div class="modal fade" id="modalConfirmarEliminarTienda<?= $p['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="?c=pedidos&accion=cancelarPedido" method="POST">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Cancelar pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas cancelar el pedido nro <?= htmlspecialchars($p['id']) ?> de <?= htmlspecialchars($p['nombre_proveedor']) ?>?
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

    <!-- Modal Agregar Pedido Tienda -->
    <div class="modal fade" id="modalAgregarPT" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="?c=pedidos&accion=insertTienda" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Pedido a Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="idProveedorTienda" class="form-label">Proveedor</label>
                            <select name="id_proveedor" id="idProveedorTienda" class="form-select no-select2" required>
                                <option value="">-- Seleccione un proveedor --</option>
                                <?php foreach ($proveedores as $prov): ?>
                                    <option value="<?= $prov['id'] ?>"><?= htmlspecialchars(!empty($prov['razon_social']) ? $prov['razon_social'] : $prov['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 text-end">
                            <h4 class="mt-4">Costo Total: $<span id="totalPedidoTienda">0.00</span></h4>
                        </div>
                    </div>
                    
                    <input type="hidden" name="estado" value="pendiente" />
                    <input type="hidden" name="tipo" value="propios" />

                    <h5 class="mt-4 border-bottom pb-2">Detalles (Productos del Pedido)</h5>
                    <div id="detallesContainerTienda">
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-sm btn-secondary" id="addDetalleTienda">+ Agregar detalle</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar Pedido</button>
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