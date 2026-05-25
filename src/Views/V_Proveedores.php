<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/css/libs/intlTelInput.css">
    <script src="assets/js/libs/intlTelInput.min.js" defer></script>
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/proveedores.js" defer></script>
    <script src="assets/js/pages/proveedores.js" defer></script>
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Proveedores</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="container bg-white p-4 rounded shadow-sm">
            <!-- Tabs de Bootstrap principales -->
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-proveedores" data-bs-toggle="tab" data-bs-target="#modulo-proveedores" type="button">Proveedores</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-entradas" data-bs-toggle="tab" data-bs-target="#modulo-entradas" type="button">Entradas</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Modulo Proveedores -->
                <div class="tab-pane fade show active" id="modulo-proveedores">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Proveedores</h1>
                        <div>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Proveedor</button>
                        </div>
                    </div>

                    <!-- Sub-Tabs de Proveedores (Activos/Inactivos) -->
                    <ul class="nav nav-tabs mb-3" id="proveedorTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button">Activos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="inactivos-tab" data-bs-toggle="tab" data-bs-target="#inactivos" type="button">Inactivos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Proveedores Activos -->
                        <div class="tab-pane fade show active" id="activos">
                            <div class="table-responsive">
                                <table id="tablaActivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Razon social</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Dirección</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proveedores as $p): ?>
                                            <tr>
                                                <td><?php echo $p['nombre']; ?></td>
                                                <td><?php echo $p['rif']; ?></td>
                                                <td><?php echo $p['telefono']; ?></td>
                                                <td><?php echo $p['email']; ?></td>
                                                <td><?php echo $p['direccion']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $p['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminar<?= $p['id'] ?>">Eliminar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="modalEditar<?= $p['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=proveedores&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Proveedor</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="nombre" class="form-label">Nombre</label>
                                                                        <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo $p['nombre'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="rif" class="form-label">Razon social</label>
                                                                        <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="Razon social" value="<?php echo $p['rif'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="telefono" class="form-label">Teléfono</label>
                                                                        <input type="tel" class="form-control phone-input" inputmode="tel" title="Ingrese un teléfono válido" name="telefono" placeholder="Teléfono" value="<?php echo $p['telefono'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $p['email'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="direccion" class="form-label">Dirección</label>
                                                                        <input type="text" minlength="5" maxlength="255" name="direccion" class="form-control" title="Mínimo 5 caracteres" placeholder="Dirección" value="<?php echo $p['direccion'] ?>" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                                                        <form action="?c=proveedores&accion=delete" method="POST">
                                                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas inhabilitar a <?= htmlspecialchars($p['nombre']) ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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

                        <!-- Proveedores Inactivos -->
                        <div class="tab-pane fade" id="inactivos">
                            <div class="table-responsive">
                                <table id="tablaInactivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Razon social</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Dirección</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proveedoresInactivos as $pIN): ?>
                                            <tr>
                                                <td><?php echo $pIN['nombre']; ?></td>
                                                <td><?php echo $pIN['rif']; ?></td>
                                                <td><?php echo $pIN['telefono']; ?></td>
                                                <td><?php echo $pIN['email']; ?></td>
                                                <td><?php echo $pIN['direccion']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarIN<?= $pIN['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivar<?= $pIN['id'] ?>">Activar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Editar Inactivos -->
                                            <div class="modal fade" id="modalEditarIN<?= $pIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=proveedores&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $pIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Proveedor</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="nombre" class="form-label">Nombre</label>
                                                                        <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo $pIN['nombre'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="rif" class="form-label">Razon social</label>
                                                                        <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="Razon social" value="<?php echo $pIN['rif'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="telefono" class="form-label">Teléfono</label>
                                                                            <input type="tel" class="form-control phone-input" inputmode="tel" title="Ingrese un teléfono válido" name="telefono" placeholder="Teléfono" value="<?php echo $pIN['telefono'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $pIN['email'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="direccion" class="form-label">Dirección</label>
                                                                        <input type="text" minlength="5" maxlength="255" name="direccion" class="form-control" title="Mínimo 5 caracteres" placeholder="Dirección" value="<?php echo $pIN['direccion'] ?>" required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
                                                        <form action="?c=proveedores&accion=active" method="POST">
                                                            <input type="hidden" name="id" value="<?= $pIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar activación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas habilitar a <?= htmlspecialchars($pIN['nombre']) ?>?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
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

                <!-- Modulo Entradas -->
                <div class="tab-pane fade" id="modulo-entradas">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Entradas</h1>
                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#entradaModal">
                            + Nueva Entrada
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID Entrada</th>
                                    <th scope="col">Lote</th>
                                    <th scope="col">Proveedor</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Total ($)</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($entradasLista)): ?>
                                    <?php foreach ($entradasLista as $ent): ?>
                                    <tr>
                                        <td><?= $ent['id'] ?></td>
                                        <td><?= htmlspecialchars($ent['numero_lote']) ?></td>
                                        <td><?= htmlspecialchars($ent['proveedor_nombre']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($ent['fecha_ingreso'])) ?></td>
                                        <td><?= number_format($ent['total'], 2) ?></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-info btn-sm text-white ver-detalle-entrada" data-id="<?= $ent['id'] ?>" title="Ver Detalles">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                                  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay entradas registradas.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal Agregar Proveedor -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <form action="?c=proveedores&accion=insert" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Proveedor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" placeholder="Nombre" required />
                        </div>
                        <div class="col-md-6">
                            <label for="rif" class="form-label">Razon social</label>
                            <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="Razon social" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <label for="tipo_persona" class="form-label">Tipo de Persona</label>
                            <select class="form-select" name="tipo_persona" id="tipo_persona" required>
                                <option value="" disabled selected>Seleccione un tipo de persona</option>
                                <option value="V-">Natural (V)</option>
                                <option value="J-">Jurídica (J)</option>
                                <option value="E-">Extranjera (E)</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" pattern="[0-9]{6,8}" title="Solo números, entre 6 y 8 caracteres" name="cedula" id="cedula" placeholder="Número de Cédula" required />
                            <div id="mensaje-cedula" style="color: red; margin-top: 5px;"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control phone-input" inputmode="tel" title="Ingrese un teléfono válido" name="telefono" placeholder="Teléfono" required />
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" minlength="5" maxlength="255" name="direccion" class="form-control" title="Mínimo 5 caracteres" placeholder="Dirección" required />
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

    <!-- formulario de registro de entradas -->
    <div class="modal fade" id="entradaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Entrada (Compra)</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="?c=entradas&accion=insert" id="entradaForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="id_proveedor" class="form-label">Proveedor</label>
                                <select class="form-select" name="id_proveedor" id="id_proveedor" required>
                                    <option value="" disabled selected>Seleccione un proveedor</option>
                                    <?php foreach ($proveedores as $prov): ?>
                                        <option value="<?= $prov['id'] ?>"><?= htmlspecialchars($prov['nombre']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="numero_de_lote" class="form-label">Número de Lote / Factura</label>
                                <input type="text" class="form-control" name="numero_lote" id="numero_de_lote" placeholder="Lote" required />
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" value="<?= date('Y-m-d') ?>" required />
                            </div>
                        </div>

                        <hr>
                        <h5>Productos</h5>
                        <div id="productos_container">
                            <div class="row mb-2 producto-row">
                                <div class="col-md-5">
                                    <select class="form-select" name="id_producto[]" required>
                                        <option value="" disabled selected>Seleccione un producto</option>
                                        <?php if(isset($productosDisponibles)) { foreach ($productosDisponibles as $prod): ?>
                                            <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                                        <?php endforeach; } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" min="1" required>
                                </div>
                                <div class="col-md-3">
                                    <input type="number" step="0.01" class="form-control" name="precio_compra[]" placeholder="Precio ($)" min="0.01" required>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remover-producto mt-1">X</button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mb-3" id="btn_add_producto">+ Añadir otro producto</button>

                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            <button type='submit' class='btn btn-primary'>Guardar Entrada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="entradasInsumoModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles de la Entrada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoEntradasInsumo">
                    
                </div>
            </div>
        </div>
    </div>

</body>
</html>