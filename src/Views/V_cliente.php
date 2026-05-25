<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/tabla.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/cliente.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">

    <title>cliente</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titulo text-black">Clientes</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Cliente</button>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div><?= htmlspecialchars($error); ?></div>
            </div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>
        <div class="container bg-white p-4 rounded shadow-sm">
            <!-- Tabs de Bootstrap -->
            <ul class="nav nav-tabs mb-3" id="clienteTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button">Activos</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="inactivos-tab" data-bs-toggle="tab" data-bs-target="#inactivos" type="button">Inactivos</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Clientes Activos -->
                <div class="tab-pane fade show active" id="activos">
                    <div class="table-responsive">
                        <table id="tablaActivos" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Fecha de registro</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $c): ?>
                                    <tr>
                                        <td><?php echo $c['cedula']; ?></td>
                                        <td><?php echo $c['nombre']; ?></td>
                                        <td><?php echo $c['apellido']; ?></td>
                                        <td><?php echo $c['correo']; ?></td>
                                        <td><?php echo $c['telefono']; ?></td>
                                        <td><?php echo $c['direccion']; ?></td>
                                        <td><?php echo $c['fecha_registro']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $c['id'] ?>">Editar</button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminar<?= $c['id'] ?>">Eliminar</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditar<?= $c['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=update" method="POST">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Cliente</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="nombre" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" value="<?php echo $c['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" value="<?php echo $c['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $c['telefono'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" value="<?php echo $c['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $c['direccion'] ?>" required />
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
                                    <div class="modal fade" id="modalConfirmarEliminar<?= $c['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=delete" method="POST">
                                                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas inhabilitar a <?= htmlspecialchars($c['nombre']) ?>?
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

                <!-- Clientes Inactivos -->
                <div class="tab-pane fade" id="inactivos">
                    <div class="table-responsive">
                        <table id="tablaInactivos" class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Telefono</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Fecha de registro</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($clientesInactivos as $cIN): ?>
                                        <tr>
                                        <td><?php echo $cIN['cedula']; ?></td>
                                        <td><?php echo $cIN['nombre']; ?></td>
                                        <td><?php echo $cIN['apellido']; ?></td>
                                        <td><?php echo $cIN['correo']; ?></td>
                                        <td><?php echo $cIN['telefono']; ?></td>
                                        <td><?php echo $cIN['direccion']; ?></td>
                                        <td><?php echo $cIN['fecha_registro']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $cIN['id'] ?>">Editar</button>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivar<?= $cIN['id'] ?>">Activar</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditar<?= $cIN['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=update" method="POST">
                                                    <input type="hidden" name="id" value="<?= $cIN['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Cliente</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="nombre" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" max    ength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" value="<?php echo $cIN['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" value="<?php echo $cIN['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $cIN['telefono'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" value="<?php echo $cIN['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $cIN['direccion'] ?>" required />
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
                                    <div class="modal fade" id="modalConfirmarActivar<?= $cIN['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=active" method="POST">
                                                    <input type="hidden" name="id" value="<?= $cIN['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmar activación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas habilitar a <?= htmlspecialchars($cIN['nombre']) ?>?
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
</body>




<!-- TEST -->
             <!-- Modal Agregar Cliente -->
        <div class="modal fade" id="modalAgregar" tabindex="-1">
            <div class="modal-dialog">
                <form action="?c=clientes&accion=insert" method="POST" class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Agregar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required />
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" id="telefono" name="telefono" placeholder="Teléfono" required />
                        </div>
                        <div class="col-md-6">
                            <label for="correo" class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion" title="Entre 5 y 25 caracteres" placeholder="Dirección" required />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

</html>