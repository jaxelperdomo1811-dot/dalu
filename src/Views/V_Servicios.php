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
    <script src="assets/js/pages/pedidos2.js" defer></script>
    <script src="assets/js/pages/servicios.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Servicios</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $pedidosC = $pedidosC ?? [];
        $clientes = $clientes ?? [];
        $productos = $productos ?? [];
        
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
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $p['id'] ?>">Detalles</button>
                                            <button type="button" class="btn btn-sm btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalAvanzarEstadoCliente<?= $p['id'] ?>" <?= in_array($p['estado'], ['entregado','cancelado']) ? ' disabled' : '' ?>>Siguiente estado</button>
                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarCliente<?= $p['id'] ?>">Cancelar</button>
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
        <div class="modal-dialog">
            <form action="?c=servicios&accion=insertCliente" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Pedido de Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Cédula del Cliente</label>
                        <div class="input-group">
                            <select name="tipo_persona" class="form-select" id="pedido_tipo_persona" style="max-width: 80px;">
                                <option value="V-">V-</option>
                                <option value="E-">E-</option>
                                <option value="J-">J-</option>
                            </select>
                            <input type="text" name="cedula_cliente" class="form-control" id="pedido_cedula_cliente" placeholder="Número de cédula" required>
                        </div>
                        <div id="mensaje_pedido_cedula" class="form-text"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nombre del Cliente</label>
                        <input type="text" name="nombre_cliente" class="form-control" id="pedido_nombre_cliente" readonly placeholder="Se autocompletará al buscar o ingréselo manualmente...">
                        <input type="hidden" name="id_cliente" id="pedido_id_cliente">
                    </div>
                    <input type="hidden" name="estado" value="pendiente" />
                    <input type="hidden" name="tipo" value="cliente" />
                    <input type="hidden" name="from_servicios" value="1">

                    <!-- Detalle opcional -->
                    <div id="detallesContainerCliente">
                        <div class="detalle-row d-flex gap-2 mb-2" data-index="0">
                            <input type="hidden" name="detalles[0][tipo]" value="producto">
                            <select name="detalles[0][id_producto]" class="form-select form-select-sm">
                                <option value="">-- Producto existente --</option>
                                <?php foreach ($productos as $prod): ?>
                                    <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="text" name="detalles[0][nombre_producto]" class="form-control" placeholder="Nombre producto (opcional)">
                            <input type="number" name="detalles[0][cantidad]" class="form-control" placeholder="Cantidad" min="1" value="1">
                            <input type="text" name="detalles[0][link]" class="form-control" placeholder="Link (opcional)">
                            <input type="file" name="detalleImagens[0]" accept="image/*" class="form-control form-control-sm">
                            <button type="button" class="btn btn-sm btn-danger btn-remove-detalle">Eliminar</button>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-secondary" id="addDetalleCliente">+ Agregar detalle</button>
                    </div>
                    <input type="hidden" name="detalles[0][estado]" value="pendiente" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
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
