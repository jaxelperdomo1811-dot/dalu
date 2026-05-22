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
    <script src="assets/js/usuarios.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">


    <title>Usuario</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titulo text-black">Usuarios</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Usuario</button>
        </div>
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
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Clave</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $u): ?>
                                    <tr>
                                        <td><?php echo $u['nombre']; ?></td>
                                        <td><?php echo $u['usuario']; ?></td>
                                        <td><?php echo $u['clave']; ?></td>
                                        <td><?php echo $u['rol']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $u['id'] ?>">Editar</button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarUsuario<?= $u['id'] ?>">Eliminar</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditarUsuario<?= $u['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=update" method="POST">
                                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Cliente</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="nombre" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" value="<?php echo $u['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" value="<?php echo $u['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $u['telefono'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" value="<?php echo $u['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $u['direccion'] ?>" required />
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
                                    <div class="modal fade" id="modalConfirmarEliminarUsuario<?= $u['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=usuarios&accion=delete" method="POST">
                                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmar eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas inhabilitar a <?= htmlspecialchars($u['nombre']) ?>?
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
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Usuario</th>
                                    <th scope="col">Clave</th>
                                    <th scope="col">Rol</th>
                                    <th scope="col">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php foreach ($usuariosInactivos as $uIN): ?>
                                        <tr>
                                        <td><?php echo $uIN['nombre']; ?></td>
                                        <td><?php echo $uIN['usuario']; ?></td>
                                        <td><?php echo $uIN['clave']; ?></td>
                                        <td><?php echo $uIN['rol']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $uIN['id'] ?>">Editar</button>
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivarUsuario<?= $uIN['id'] ?>">Activar</button>
                                        </td>
                                    </tr>
                                    <!-- Modal Editar -->
                                    <div class="modal fade" id="modalEditarUsuario<?= $uIN['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=clientes&accion=update" method="POST">
                                                    <input type="hidden" name="id" value="<?= $uIN['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Editar Cliente</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="nombre" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" max    ength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" value="<?php echo $uIN['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" value="<?php echo $uIN['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono" class="form-label">Teléfono</label>
                                                                <input type="text" class="form-control" pattern="[0-9]{9,11}" title="Ingrese solo números, entre 9 y 11 caracteres" id="telefono" name="telefono" placeholder="Teléfono" value="<?php echo $uIN['telefono'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo" name="correo" placeholder="Correo" value="<?php echo $uIN['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $uIN['direccion'] ?>" required />
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
                                    <div class="modal fade" id="modalConfirmarActivarUsuario<?= $uIN['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="?c=usuarios&accion=active" method="POST">
                                                    <input type="hidden" name="id" value="<?= $uIN['id'] ?>">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Confirmar activación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        ¿Estás seguro de que deseas habilitar a <?= htmlspecialchars($uIN['nombre']) ?>?
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

    <!-- Modal Agregar Usuario -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="?c=usuarios&accion=insert" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rolAgregar" class="form-label">Rol</label>
                        <select class="form-select" name="id_rol" id="rolAgregar" required>
                            <option value="" disabled selected>Seleccione un rol</option>
                            <?php foreach ($roles as $rol): ?>
                                <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nombreAgregar" class="form-label">Nombre</label>
                        <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombreAgregar" placeholder="Nombre" required />
                    </div>
                    <div class="mb-3">
                        <label for="usuarioAgregar" class="form-label">Usuario</label>
                        <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z0-9_]{3,20}" title="Ingrese solo letras, números o guiones bajos, entre 3 y 20 caracteres" name="usuario" class="form-control" id="usuarioAgregar" placeholder="Usuario" required />
                    </div>
                    <div class="mb-3">
                        <label for="claveAgregar" class="form-label">Clave</label>
                        <input type="password" minlength="6" maxlength="20" name="clave" class="form-control" id="claveAgregar" placeholder="Clave" required />
                    </div>
                    <div class="mb-3">
                        <label for="clave2Agregar" class="form-label">Repetir Clave</label>
                        <input type="password" minlength="6" maxlength="20" name="clave2" class="form-control" id="clave2Agregar" placeholder="Repetir Clave" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>