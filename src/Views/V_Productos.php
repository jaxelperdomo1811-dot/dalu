<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Productos</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="container bg-white p-4 rounded shadow-sm">
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-productos" data-bs-toggle="tab" data-bs-target="#productos"
                        type="button">Productos</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-categorias" data-bs-toggle="tab" data-bs-target="#categorias"
                        type="button">Categoría de Productos</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-inventario" data-bs-toggle="tab" data-bs-target="#inventario"
                        type="button">Inventario</button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="productos">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Productos</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo
                        Producto</button>
                    </div>

                    <!-- Tabs de Bootstrap -->
                    <ul class="nav nav-tabs mb-3" id="productoTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos"
                                type="button">Activos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="inactivos-tab" data-bs-toggle="tab" data-bs-target="#inactivos"
                                type="button">Inactivos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Productos Activos -->
                        <div class="tab-pane fade show active" id="activos">
                            <div class="table-responsive">
                                <table id="tablaActivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Categoria</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Miniatura</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio($)</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Fecha de registro</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $p): ?>
                                        <tr>
                                            <td><?php echo $p['categoria_nombre'] ?? $p['categoria']; ?></td>
                                            <td><?php echo $p['nombre']; ?></td>
                                            <td>
                                                <?php $thumb = !empty($p['imagen_principal']) ? $p['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                                <img src="<?= htmlspecialchars($thumb) ?>" alt="miniatura" class="img-thumbnail product-thumb" style="width:56px;height:56px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($thumb) ?>" />
                                            </td>
                                            <td><?php echo $p['descripcion']; ?></td>
                                            <td><?php echo $p['precio_venta'] ?? $p['precio']; ?></td>
                                            <td><?php echo $p['stock_total'] ?? ($p['stock_total'] ?? 0); ?></td>
                                            <td><?php echo $p['fecha_registro']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar<?= $p['id'] ?>">Editar</button>
                                                <button type="button" class="btn btn-sm btn-info view-variants-btn" data-id="<?= $p['id'] ?>">Variantes (<?= $p['total_variantes'] ?? 0 ?>)</button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                    data-bs-target="#modalConfirmarEliminar<?= $p['id'] ?>">Desactivar</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Productos Inactivos -->
                        <div class="tab-pane fade" id="inactivos">
                            <div class="table-responsive">
                                <table id="tablaInactivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Categoria</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Miniatura</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Precio($)</th>
                                            <th scope="col">Stock</th>
                                            <th scope="col">Fecha de registro</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                                                        <tbody>
                                        <?php foreach ($productosInactivos as $pIN): ?>
                                        <tr>
                                            <td><?php echo $pIN['categoria_nombre'] ?? $pIN['categoria']; ?></td>
                                            <td><?php echo $pIN['nombre']; ?></td>
                                            <td>
                                                <?php $thumbIN = !empty($pIN['imagen_principal']) ? $pIN['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                                <img src="<?= htmlspecialchars($thumbIN) ?>" alt="miniatura" class="img-thumbnail product-thumb" style="width:56px;height:56px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($thumbIN) ?>" />
                                            </td>
                                            <td><?php echo $pIN['descripcion']; ?></td>
                                            <td><?php echo $pIN['precio_venta'] ?? $pIN['precio']; ?></td>
                                            <td><?php echo $pIN['stock_total'] ?? ($pIN['stock_total'] ?? 0); ?></td>
                                            <td><?php echo $pIN['fecha_registro']; ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                    data-bs-target="#modalEditar<?= $pIN['id'] ?>">Editar</button>
                                                <button type="button" class="btn btn-sm btn-info view-variants-btn" data-id="<?= $pIN['id'] ?>">Variantes (<?= $pIN['total_variantes'] ?? 0 ?>)</button>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#modalConfirmarActivar<?= $pIN['id'] ?>">Activar</button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="categorias">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Categoría de Productos</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarCategoria">+ Nueva
                        Categoría</button>
                    </div>

                    <!-- Tabs de Bootstrap -->
                    <ul class="nav nav-tabs mb-3" id="categoriaTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="cat_activos-tab" data-bs-toggle="tab" data-bs-target="#catActivos"
                                type="button">Activos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="cat_inactivos-tab" data-bs-toggle="tab" data-bs-target="#catInactivos"
                                type="button">Inactivos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Categorías Activas -->
                        <div class="tab-pane fade show active" id="catActivos">
                            <div class="table-responsive">
                                <table id="tablaCatActivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Fecha de registro</th>
                                            <th scope="col">Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            <?php foreach ($categorias as $c): ?>
                                            <tr>
                                                <td><?php echo $c['nombre']; ?></td>
                                                <td><?php echo $c['descripcion']; ?></td>
                                                <td><?php echo $c['fecha_registro']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCategoria<?= $c['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#modalConfirmarEliminarCategoria<?= $c['id'] ?>">Desactivar</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="modalEditarCategoria<?= $c['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=categorias&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Categoria</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nombre_input" class="form-label">Nombre</label>
                                                                    <input type="text" class="form-control" id="nombre_input" name="nombre" value="<?= $c['nombre'] ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="descripcion_input" class="form-label">Descripción</label>
                                                                    <textarea class="form-control" id="descripcion_input" name="descripcion"
                                                                        rows="3"><?= $c['descripcion'] ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Confirmar Eliminación -->
                                            <div class="modal fade" id="modalConfirmarEliminarCategoria<?= $c['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=categorias&accion=delete" method="POST">
                                                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas inhabilitar la categoría <?= htmlspecialchars($c['nombre']) ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-danger">Eliminar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Categorias Inactivas -->

                            <div class="tab-pane fade" id="catInactivos">
                                <div class="table-responsive">
                                    <table id="tablaCatInactivos" class="table-DT table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Fecha de registro</th>
                                                <th scope="col">Accion</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($categoriasInactivas as $cIN): ?>
                                            <tr>
                                                <td><?php echo $cIN['nombre']; ?></td>
                                                <td><?php echo $cIN['descripcion']; ?></td>
                                                <td><?php echo $cIN['fecha_registro']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#modalEditarCategoria<?= $cIN['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#modalConfirmarActivarCategoria<?= $cIN['id'] ?>">Activar</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="modalEditarCategoria<?= $cIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=categorias&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $cIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Categoría</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="nombre_input" class="form-label">Nombre</label>
                                                                    <input type="text" class="form-control" id="nombre_input" name="nombre" value="<?= $cIN['nombre'] ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="descripcion_input" class="form-label">Descripción</label>
                                                                    <textarea class="form-control" id="descripcion_input" name="descripcion"
                                                                        rows="3"><?= $cIN['descripcion'] ?></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cerrar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Modal Confirmar Activación -->
                                            <div class="modal fade" id="modalConfirmarActivarCategoria<?= $cIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=categorias&accion=active" method="POST">
                                                            <input type="hidden" name="id" value="<?= $cIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar activación</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas habilitar la categoria <?= htmlspecialchars($cIN['nombre']) ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-success">Activar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; ?>
                                        </tbody>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- Inventario -->
                <div class="tab-pane fade" id="inventario">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Inventario de Productos</h1>
                    </div>

                    <div class="table-responsive">
                        <table id="tablaInventario" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Categoría</th>
                                    <th scope="col">Nombre del Producto</th>
                                    <th scope="col">Miniatura</th>
                                    <th scope="col">Stock Actual</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($productos as $p): ?>
                                    <tr>
                                        <td><?php echo $p['categoria_nombre'] ?? $p['categoria']; ?></td>
                                        <td><?php echo $p['nombre']; ?></td>
                                        <td>
                                            <?php $thumb = !empty($p['imagen_principal']) ? $p['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                            <img src="<?= htmlspecialchars($thumb) ?>" alt="miniatura" class="img-thumbnail product-thumb" style="width:56px;height:56px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($thumb) ?>" />
                                        </td>
                                        <td>
                                            <?php echo $p['stock_total'] ?? ($p['stock_total'] ?? 0); ?>
                                            <?php if (($p['stock_total'] ?? 0) <= $p['stock_minimo']): ?>
                                                <span class="badge bg-danger ms-2">Stock Bajo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Activo</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php foreach ($productosInactivos as $pIN): ?>
                                    <tr>
                                        <td><?php echo $pIN['categoria_nombre'] ?? $pIN['categoria']; ?></td>
                                        <td><?php echo $pIN['nombre']; ?></td>
                                        <td>
                                            <?php $thumbIN = !empty($pIN['imagen_principal']) ? $pIN['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                            <img src="<?= htmlspecialchars($thumbIN) ?>" alt="miniatura" class="img-thumbnail product-thumb" style="width:56px;height:56px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($thumbIN) ?>" />
                                        </td>
                                        <td><?php echo $pIN['stock_total'] ?? ($pIN['stock_total'] ?? 0); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">Inactivo</span>
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

    <!-- MODALES EXTRAÍDOS PARA EVITAR CONFLICTOS DE DOM -->
<?php foreach ($productos as $p): ?>
<!-- Modal Editar -->
                                        <div class="modal fade modal-edit-product" id="modalEditar<?= $p['id'] ?>" tabindex="-1" 
                                             data-variantes="<?= htmlspecialchars(json_encode($p['variantes'] ?? []), ENT_QUOTES) ?>"
                                             data-precio-oferta="<?= htmlspecialchars($p['precio_oferta'] ?? '') ?>"
                                             data-stock-minimo="<?= htmlspecialchars($p['stock_minimo'] ?? '3') ?>">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="?c=productos&accion=update" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Editar Producto</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="categoria_<?= $p['id'] ?>" class="form-label">Categoría</label>
                                                                <select class="form-select" name="id_categoria" id="categoria_<?= $p['id'] ?>" required>
                                                                    <option value="<?= $p['id_categoria'] ?>" selected><?= $p['categoria_nombre'] ?? $p['categoria'] ?></option>
                                                                    <?php foreach ($categorias as $categoria): ?>
                                                                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <!-- Contenedor para campos dinámicos según categoría -->
                                                                <div id="dynamic-attributes-edit-<?= $p['id'] ?>" class="mt-3"></div>

                                                                <?php if (!empty($p['variantes_inactivas'])): ?>
                                                                    <div class="mt-4 mb-3 border rounded p-3 bg-light">
                                                                        <h6 class="text-muted mb-3"><i class="fa fa-trash me-2"></i>Variantes Inactivas / Eliminadas</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-bordered mb-0 bg-white">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>Variante</th>
                                                                                        <th class="text-center">Stock</th>
                                                                                        <th class="text-center">Acción</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($p['variantes_inactivas'] as $vi): ?>
                                                                                        <tr>
                                                                                            <td class="align-middle text-muted text-decoration-line-through"><?= htmlspecialchars($vi['nombre_variante']) ?></td>
                                                                                            <td class="align-middle text-center text-muted"><?= htmlspecialchars($vi['stock']) ?></td>
                                                                                            <td class="align-middle text-center">
                                                                                                <div class="form-check form-switch d-inline-block">
                                                                                                    <input class="form-check-input" type="checkbox" name="reactivate_variants[]" value="<?= $vi['id'] ?>" id="reactivate_<?= $vi['id'] ?>">
                                                                                                    <label class="form-check-label" for="reactivate_<?= $vi['id'] ?>">Restaurar</label>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <small class="text-muted d-block mt-2">Marca la casilla "Restaurar" y guarda el producto para volver a activar la variante.</small>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="mb-3">
                                                                <?php $currentThumb = !empty($p['imagen_principal']) ? $p['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                                                <label class="form-label">Imagen actual</label>
                                                                <div class="mb-2">
                                                                    <img src="<?= htmlspecialchars($currentThumb) ?>" alt="imagen actual" class="img-thumbnail product-thumb" style="width:100px;height:100px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($currentThumb) ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="nombre_<?= $p['id'] ?>" class="form-label">Nombre</label>
                                                                <input type="text" class="form-control" id="nombre_<?= $p['id'] ?>" name="nombre" value="<?= $p['nombre'] ?>" required />
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="precio_venta_<?= $p['id'] ?>" class="form-label">Precio</label>
                                                                    <input type="number" step="0.01" min="0" id="precio_venta_<?= $p['id'] ?>" name="precio_venta" class="form-control" value="<?= htmlspecialchars($p['precio_venta'] ?? $p['precio'] ?? '') ?>" required />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="precio_compra_<?= $p['id'] ?>" class="form-label">Precio compra</label>
                                                                    <input type="number" step="0.01" min="0" id="precio_compra_<?= $p['id'] ?>" name="precio_compra" class="form-control" value="<?= htmlspecialchars($p['precio_compra'] ?? '') ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="marca_<?= $p['id'] ?>" class="form-label">Marca</label>
                                                                    <input type="text" id="marca_<?= $p['id'] ?>" name="marca" class="form-control" value="<?= htmlspecialchars($p['marca'] ?? '') ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="descripcion_<?= $p['id'] ?>" class="form-label">Descripción</label>
                                                                <textarea class="form-control" id="descripcion_<?= $p['id'] ?>" name="descripcion"
                                                                    rows="3"><?= $p['descripcion'] ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Confirmar Eliminación -->
                                        <div class="modal fade" id="modalConfirmarEliminar<?= $p['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="?c=productos&accion=delete" method="POST">
                                                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar eliminación</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de que deseas inhabilitar el producto <?= htmlspecialchars($p['nombre']) ?>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>

                                        <?php foreach ($productosInactivos as $pIN): ?>
<!-- Modal Editar -->
                                        <div class="modal fade modal-edit-product" id="modalEditar<?= $pIN['id'] ?>" tabindex="-1" 
                                             data-variantes="<?= htmlspecialchars(json_encode($pIN['variantes'] ?? []), ENT_QUOTES) ?>"
                                             data-precio-oferta="<?= htmlspecialchars($pIN['precio_oferta'] ?? '') ?>"
                                             data-stock-minimo="<?= htmlspecialchars($pIN['stock_minimo'] ?? '3') ?>">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="?c=productos&accion=update" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="id" value="<?= $pIN['id'] ?>">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Editar Producto</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="categoria_<?= $pIN['id'] ?>" class="form-label">Categoría</label>
                                                                <select class="form-select" name="id_categoria" id="categoria_<?= $pIN['id'] ?>" required>
                                                                    <option value="<?= $pIN['id_categoria'] ?>" selected><?= $pIN['categoria_nombre'] ?? $pIN['categoria'] ?></option>
                                                                    <?php foreach ($categorias as $categoria): ?>
                                                                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <!-- Contenedor para campos dinámicos según categoría -->
                                                                <div id="dynamic-attributes-edit-<?= $pIN['id'] ?>" class="mt-3"></div>

                                                                <?php if (!empty($pIN['variantes_inactivas'])): ?>
                                                                    <div class="mt-4 mb-3 border rounded p-3 bg-light">
                                                                        <h6 class="text-muted mb-3"><i class="fa fa-trash me-2"></i>Variantes Inactivas / Eliminadas</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-bordered mb-0 bg-white">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th>Variante</th>
                                                                                        <th class="text-center">Stock</th>
                                                                                        <th class="text-center">Acción</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($pIN['variantes_inactivas'] as $vi): ?>
                                                                                        <tr>
                                                                                            <td class="align-middle text-muted text-decoration-line-through"><?= htmlspecialchars($vi['nombre_variante']) ?></td>
                                                                                            <td class="align-middle text-center text-muted"><?= htmlspecialchars($vi['stock']) ?></td>
                                                                                            <td class="align-middle text-center">
                                                                                                <div class="form-check form-switch d-inline-block">
                                                                                                    <input class="form-check-input" type="checkbox" name="reactivate_variants[]" value="<?= $vi['id'] ?>" id="reactivate_in_<?= $vi['id'] ?>">
                                                                                                    <label class="form-check-label" for="reactivate_in_<?= $vi['id'] ?>">Restaurar</label>
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <small class="text-muted d-block mt-2">Marca la casilla "Restaurar" y guarda el producto para volver a activar la variante.</small>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="mb-3">
                                                                <?php $currentThumbIN = !empty($pIN['imagen_principal']) ? $pIN['imagen_principal'] : 'assets/img/products/default.jpeg'; ?>
                                                                <label class="form-label">Imagen actual</label>
                                                                <div class="mb-2">
                                                                    <img src="<?= htmlspecialchars($currentThumbIN) ?>" alt="imagen actual" class="img-thumbnail product-thumb" style="width:100px;height:100px;object-fit:cover;cursor:pointer;" data-src="<?= htmlspecialchars($currentThumbIN) ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="nombre_<?= $pIN['id'] ?>" class="form-label">Nombre</label>
                                                                <input type="text" class="form-control" id="nombre_<?= $pIN['id'] ?>" name="nombre" value="<?= $pIN['nombre'] ?>" required />
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="precio_venta_<?= $pIN['id'] ?>" class="form-label">Precio</label>
                                                                    <input type="number" step="0.01" min="0" id="precio_venta_<?= $pIN['id'] ?>" name="precio_venta" class="form-control" value="<?= htmlspecialchars($pIN['precio_venta'] ?? $pIN['precio'] ?? '') ?>" required />
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="precio_compra_<?= $pIN['id'] ?>" class="form-label">Precio compra</label>
                                                                    <input type="number" step="0.01" min="0" id="precio_compra_<?= $pIN['id'] ?>" name="precio_compra" class="form-control" value="<?= htmlspecialchars($pIN['precio_compra'] ?? '') ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <label for="marca_<?= $pIN['id'] ?>" class="form-label">Marca</label>
                                                                    <input type="text" id="marca_<?= $pIN['id'] ?>" name="marca" class="form-control" value="<?= htmlspecialchars($pIN['marca'] ?? '') ?>" />
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="descripcion_<?= $pIN['id'] ?>" class="form-label">Descripción</label>
                                                                <textarea class="form-control" id="descripcion_<?= $pIN['id'] ?>" name="descripcion"
                                                                    rows="3"><?= $pIN['descripcion'] ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cerrar</button>
                                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal Confirmar Activación -->
                                        <div class="modal fade" id="modalConfirmarActivar<?= $pIN['id'] ?>" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="?c=productos&accion=activate" method="POST">
                                                        <input type="hidden" name="id" value="<?= $pIN['id'] ?>">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Confirmar activación</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            ¿Estás seguro de que deseas habilitar a <?= htmlspecialchars($pIN['nombre']) ?>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancelar</button>
                                                            <button type="submit" class="btn btn-success">Activar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                
    <!-- Modal Agregar Producto -->
    <!-- Modal Agregar Producto -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <form action="?c=productos&accion=insert" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="categoria_input" class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" id="categoria_input" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <?php foreach ($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- Contenedor para campos dinámicos según categoría -->
                            <div id="dynamic-attributes" class="mt-3"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_input" class="form-label">Nombre</label>
                            <input type="text" minlength="3" maxlength="20"
                                pattern="[A-Za-z\s]{3,}"
                                title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control"
                                id="nombre_input" placeholder="Nombre" required />
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" min="0" name="precio_venta" class="form-control"
                                id="precio" placeholder="Precio" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="descripcion_input" class="form-label">Descripción</label>
                            <input type="text" minlength="5" maxlength="25" name="descripcion" class="form-control"
                                id="descripcion_input" title="Entre 5 y 25 caracteres" placeholder="Descripción" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Agregar Categoría -->
    <div class="modal fade" id="modalAgregarCategoria" tabindex="-1">
        <div class="modal-dialog">
            <form action="?c=categorias&accion=insert" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Categoría de Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre_input" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_input" name="nombre" required />
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_input" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion_input" name="descripcion"
                            rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
        <!-- Modal para ver imagen en tamaño completo -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-body p-0">
                        <button type="button" class="btn-close m-2 position-absolute end-0" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                        <img id="imageModalImg" src="" alt="Imagen" style="width:100%;height:auto;display:block;" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para ver variantes de un producto -->
        <div class="modal fade" id="variantsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Variantes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="variantsModalBody">Cargando...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exponer categorías a JS y cargar script de productos -->
        <script>
            window.productosCategories = <?php echo json_encode($categorias ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]'; ?>;
        </script>
    <script>
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.tagName === 'FORM') {
                const formData = new FormData(e.target);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        data[key] = value.name + ' (File)';
                    } else {
                        data[key] = value;
                    }
                }
                // Send it to a logging endpoint
                fetch('?c=productos&accion=log_form', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(data)
                });
            }
        });
    </script>
        <script src="assets/js/pages/productos.js" defer></script>
    </body>

    </html>