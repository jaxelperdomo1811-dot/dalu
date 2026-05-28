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
    <script src="assets/js/citas.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
        <script src="assets/js/pages/pedidos2.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <title>Pedidos</title>
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
                <li class="nav-item">
                    <button class="nav-link" id="tab-clientes" data-bs-toggle="tab" data-bs-target="#modulo-clientes" type="button">Clientes</button>
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
                                            <th scope="col">Fecha Pedido</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($pedidos as $p): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($p['id']); ?></td>
                                                <td><?php echo htmlspecialchars($p['nombre_proveedor']); ?></td>
                                                <td><?php echo htmlspecialchars($p['fecha_pedido']); ?></td>
                                                <td><?php echo htmlspecialchars($p['estado']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarTienda<?= $p['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarTienda<?= $p['id'] ?>">Desactivar</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Confirmar Eliminación -->
                                            <div class="modal fade" id="modalConfirmarEliminarTienda<?= $p['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=usuarios&accion=delete" method="POST">
                                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas cambiar el estado del pedido nro <?= htmlspecialchars($p['id']) ?> de <?= htmlspecialchars($p['nombre_proveedor']) ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-danger">Aceptar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!-- Modal Agregar pedido tienda -->
                                <div class="modal fade" id="modalAgregarPT" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="?c=pedidos&accion=insertTienda" method="POST" enctype="multipart/form-data" class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Agregar Pedido Tienda</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="nombreProveedorTienda" class="form-label">Nombre del proveedor</label>
                                                    <input type="text" name="nombre_proveedor" id="nombreProveedorTienda" class="form-control" placeholder="Proveedor" required />
                                                </div>
                                                <input type="hidden" name="estado" value="pendiente" />
                                                <input type="hidden" name="tipo" value="propios" />

                                                <!-- Detalle opcional -->
                                                    
                                                    <div id="detallesContainerTienda">
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
                                                        <button type="button" class="btn btn-sm btn-secondary" id="addDetalleTienda">+ Agregar detalle</button>
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
                            </div>
                        </div>

                <!-- Modulo Clientes -->
                <div class="tab-pane fade" id="modulo-clientes">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Pedidos de los Clientes</h1>
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
                                        <td><?php echo htmlspecialchars($p['nombre_cliente']); ?></td>
                                        <td><?php echo htmlspecialchars($p['fecha_pedido']); ?></td>
                                        <td><?php echo htmlspecialchars($p['estado']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarCliente<?= $p['id'] ?>">Editar</button>
                                            <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarCliente<?= $p['id'] ?>">Desactivar</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Confirmar Eliminación -->
                                    <div class="modal fade" id="modalConfirmarEliminarCliente<?= $p['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=usuarios&accion=delete" method="POST">
                                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas cambiar el estado del pedido nro <?= htmlspecialchars($p['id']) ?> de <?= htmlspecialchars($p['nombre_proveedor']) ?>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-danger">Aceptar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Modal Agregar pedido cliente -->
                    <div class="modal fade" id="modalAgregarPC" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="?c=pedidos&accion=insertCliente" method="POST" enctype="multipart/form-data" class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Agregar Pedido de Cliente</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    
                                        <div class="mb-3">
                                            <label for="clientePedido" class="form-label">Cliente</label>
                                            <select class="form-select" name="id_cliente" id="clientePedido" required>
                                                <option value="" disabled selected>Seleccione un cliente</option>
                                                <?php foreach ($clientes as $cliente): ?>
                                                    <option value="<?= $cliente['id'] ?>"><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    <input type="hidden" name="estado" value="pendiente" />
                                    <input type="hidden" name="tipo" value="cliente" />

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
                </div>
            </div>
        </div>
    </main>
</body>
</html>