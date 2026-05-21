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
    
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/proveedores.js" defer></script>
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Proveedores</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titulo text-black">Proveedores</h1>
            <div>
                <button type="button" class="btn btn-light btn-sm ver-entradas me-2" data-id="entradasInsumoModal" data-bs-toggle="modal" data-bs-target="#entradasInsumoModal">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 1.5em; height: 1.5em;">
                        <path fill="blue" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM232 344l0-64-64 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l64 0 0-64c0-13.3 10.7-24 24-24s24 10.7 24 24l0 64 64 0c13.3 0 24 10.7 24 24s-10.7 24-24 24l-64 0 0 64c0 13.3-10.7 24-24 24s-24-10.7-24-24z" />
                    </svg>
                    Ver Entradas
                </button>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Proveedor</button>
            </div>
        </div>

        <div class="container bg-white p-4 rounded shadow-sm">
            <!-- Tabs de Bootstrap -->
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
                                            <button type="button" class="btn btn-info btn-sm m-1 text-white" data-bs-toggle="modal" data-bs-target="#entradaModal" onclick="setProveedorId(<?php echo $p['id']; ?>, '<?php echo addslashes($p['nombre']); ?>')">
                                                <b>Registrar Entrada</b>
                                            </button>
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
                                                                <label for="rif" class="form-label">RIF</label>
                                                                <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="RIF" value="<?php echo $p['rif'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" name="telefono" placeholder="Teléfono" value="<?php echo $p['telefono'] ?>" required />
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
                                    <th scope="col">RIF</th>
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
                                                                <label for="rif" class="form-label">RIF</label>
                                                                <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="RIF" value="<?php echo $pIN['rif'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" name="telefono" placeholder="Teléfono" value="<?php echo $pIN['telefono'] ?>" required />
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
                            <label for="rif" class="form-label">RIF</label>
                            <input type="text" minlength="5" maxlength="15" title="Ingrese solo texto/numeros" name="rif" class="form-control" placeholder="RIF" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" name="telefono" placeholder="Teléfono" required />
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
    <div class="modal fade" id="entradaModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="entradaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="entradaModalLabel">Registrar Entrada</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="index.php?c=entradas&a=procesarEntradaConDetalles" id="entradaForm">
                        <div class="container container-form">

                            <input type="hidden" name="id_proveedor" id="id_proveedor" value="">

                            <div class="mb-3">
                                <label for="nombre_proveedor" class="form-label">Proveedor</label>
                                <input type="text" class="form-control" name="nombre_proveedor" id="nombre_proveedor" placeholder="Nombre del Proveedor" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="numero_de_lote" class="form-label">Número de Lote</label>
                                <input type="text" class="form-control" name="numero_de_lote" id="numero_de_lote" placeholder="Número de Lote" required />
                            </div>

                            <div class="mb-3">
                                <label for="fecha_ingreso" class="form-label">Fecha de Ingreso</label>
                                <input type="date" class="form-control" name="fecha_ingreso" id="fecha_ingreso" value="<?= date('Y-m-d') ?>" required />
                            </div>

                            <div class="mb-3">
                                <label for="precio_compra" class="form-label">Precio de Compra</label>
                                <input type="number" step="0.01" class="form-control" name="precio_compra" id="precio_compra" placeholder="Precio de Compra" required />
                            </div>

                            <div class="mb-3">
                                <label for="cantidad_entrante" class="form-label">Cantidad Entrante</label>
                                <input type="number" class="form-control" name="cantidad_entrante" id="cantidad_entrante" placeholder="Cantidad Entrante" required />
                            </div>

                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            <button type='submit' class='btn btn-primary'>Registrar Entrada</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="entradasInsumoModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="entradasInsumoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl text-center">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoEntradasInsumo">
                   
                </div>
            </div>
        </div>
    </div>

</body>
</html>