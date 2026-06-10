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
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/pages/usuarios.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <title>Usuarios y Roles</title>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link <?= (!isset($_GET['tab']) || $_GET['tab'] != 'roles') ? 'active' : '' ?>" id="tab-usuarios" data-bs-toggle="tab" data-bs-target="#modulo-usuarios" type="button">Usuarios</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link <?= (isset($_GET['tab']) && $_GET['tab'] == 'roles') ? 'active' : '' ?>" id="tab-roles" data-bs-toggle="tab" data-bs-target="#modulo-roles" type="button">Roles</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Modulo Usuarios -->
                <div class="tab-pane fade <?= (!isset($_GET['tab']) || $_GET['tab'] != 'roles') ? 'show active' : '' ?>" id="modulo-usuarios">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Usuarios</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Usuario</button>
                    </div>

                    <!-- Tabs de Bootstrap para Usuarios -->
                    <ul class="nav nav-tabs mb-3" id="clienteTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button">Activos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="inactivos-tab" data-bs-toggle="tab" data-bs-target="#inactivos" type="button">Inactivos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Usuarios Activos -->
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
                                                <td><?php echo htmlspecialchars($u['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($u['usuario']); ?></td>
                                                <td><input type="password" disabled class="form-control" value="<?php echo htmlspecialchars($u['clave']); ?>"></td>
                                                <td><?php echo htmlspecialchars($u['rol']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $u['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarUsuario<?= $u['id'] ?>">Desactivar</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="modalEditarUsuario<?= $u['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=usuarios&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Usuario</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Rol</label>
                                                                    <select class="form-select" name="id_rol" required>
                                                                        <option value="" disabled>Seleccione un rol</option>
                                                                        <?php foreach ($roles as $rol): ?>
                                                                            <option value="<?= $rol['id'] ?>" <?= (isset($u['id_rol']) && $rol['id'] == $u['id_rol']) ? 'selected' : '' ?>><?= htmlspecialchars($rol['nombre']) ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre</label>
                                                                    <input type="text" minlength="3" maxlength="20" name="nombre" class="form-control" placeholder="Nombre" value="<?= htmlspecialchars($u['nombre']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Usuario</label>
                                                                    <input type="text" minlength="3" maxlength="20" name="usuario" class="form-control" placeholder="Usuario" value="<?= htmlspecialchars($u['usuario']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nueva Clave (opcional)</label>
                                                                    <input type="password" maxlength="64" name="clave" class="form-control" placeholder="Dejar en blanco para no cambiar" />
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

                        <!-- Usuarios Inactivos -->
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
                                                <td><?php echo htmlspecialchars($uIN['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($uIN['usuario']); ?></td>
                                                <td><input type="password" disabled class="form-control" value="<?php echo htmlspecialchars($uIN['clave']); ?>"></td>
                                                <td><?php echo htmlspecialchars($uIN['rol']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarUsuario<?= $uIN['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-success m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivarUsuario<?= $uIN['id'] ?>">Activar</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Editar -->
                                            <div class="modal fade" id="modalEditarUsuario<?= $uIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=usuarios&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $uIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Usuario</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Rol</label>
                                                                    <select class="form-select" name="id_rol" required>
                                                                        <option value="" disabled>Seleccione un rol</option>
                                                                        <?php foreach ($roles as $rol): ?>
                                                                            <option value="<?= $rol['id'] ?>" <?= (isset($uIN['id_rol']) && $rol['id'] == $uIN['id_rol']) ? 'selected' : '' ?>><?= htmlspecialchars($rol['nombre']) ?></option>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre</label>
                                                                    <input type="text" minlength="3" maxlength="20" name="nombre" class="form-control" placeholder="Nombre" value="<?= htmlspecialchars($uIN['nombre']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Usuario</label>
                                                                    <input type="text" minlength="3" maxlength="20" name="usuario" class="form-control" placeholder="Usuario" value="<?= htmlspecialchars($uIN['usuario']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nueva Clave (opcional)</label>
                                                                    <input type="password" maxlength="64" name="clave" class="form-control" placeholder="Dejar en blanco para no cambiar" />
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

                <!-- Modulo Roles -->
                <div class="tab-pane fade <?= (isset($_GET['tab']) && $_GET['tab'] == 'roles') ? 'show active' : '' ?>" id="modulo-roles">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Roles del Sistema</h1>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregarRol">+ Nuevo Rol</button>
                    </div>

                    <!-- Tabs de Bootstrap para Roles -->
                    <ul class="nav nav-tabs mb-3" id="rolesTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="roles-activos-tab" data-bs-toggle="tab" data-bs-target="#roles-activos" type="button">Activos</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="roles-inactivos-tab" data-bs-toggle="tab" data-bs-target="#roles-inactivos" type="button">Inactivos</button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <!-- Roles Activos -->
                        <div class="tab-pane fade show active" id="roles-activos">
                            <div class="table-responsive">
                                <table class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Rol</th>
                                            <th scope="col">Nombre del Rol</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($roles)): ?>
                                            <?php foreach ($roles as $rol): ?>
                                            <tr>
                                                <td><?= $rol['id'] ?></td>
                                                <td><?= htmlspecialchars($rol['nombre']) ?></td>
                                                <td><?= htmlspecialchars($rol['descripcion'] ?? '') ?></td>
                                                <td><span class="badge bg-success">Activo</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarRol<?= $rol['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminarRol<?= $rol['id'] ?>">Desactivar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Editar Rol -->
                                            <div class="modal fade" id="modalEditarRol<?= $rol['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=roles&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $rol['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Rol</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre del Rol</label>
                                                                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($rol['nombre']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Descripción</label>
                                                                    <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($rol['descripcion'] ?? '') ?></textarea>
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

                                            <!-- Modal Confirmar Eliminación Rol -->
                                            <div class="modal fade" id="modalConfirmarEliminarRol<?= $rol['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=roles&accion=delete" method="POST">
                                                            <input type="hidden" name="id" value="<?= $rol['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar eliminación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas inhabilitar el rol <?= htmlspecialchars($rol['nombre']) ?>?
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
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Roles Inactivos -->
                        <div class="tab-pane fade" id="roles-inactivos">
                            <div class="table-responsive">
                                <table class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">ID Rol</th>
                                            <th scope="col">Nombre del Rol</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Estado</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($rolesInactivos)): ?>
                                            <?php foreach ($rolesInactivos as $rolIN): ?>
                                            <tr>
                                                <td><?= $rolIN['id'] ?></td>
                                                <td><?= htmlspecialchars($rolIN['nombre']) ?></td>
                                                <td><?= htmlspecialchars($rolIN['descripcion'] ?? '') ?></td>
                                                <td><span class="badge bg-danger">Inactivo</span></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary m-1" data-bs-toggle="modal" data-bs-target="#modalEditarRol<?= $rolIN['id'] ?>">Editar</button>
                                                    <button type="button" class="btn btn-sm btn-success m-1" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivarRol<?= $rolIN['id'] ?>">Activar</button>
                                                </td>
                                            </tr>

                                            <!-- Modal Editar Rol Inactivo -->
                                            <div class="modal fade" id="modalEditarRol<?= $rolIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=roles&accion=update" method="POST">
                                                            <input type="hidden" name="id" value="<?= $rolIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Editar Rol</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Nombre del Rol</label>
                                                                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($rolIN['nombre']) ?>" required />
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Descripción</label>
                                                                    <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($rolIN['descripcion'] ?? '') ?></textarea>
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

                                            <!-- Modal Confirmar Activación Rol -->
                                            <div class="modal fade" id="modalConfirmarActivarRol<?= $rolIN['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <form action="?c=roles&accion=active" method="POST">
                                                            <input type="hidden" name="id" value="<?= $rolIN['id'] ?>">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Confirmar activación</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Estás seguro de que deseas habilitar el rol <?= htmlspecialchars($rolIN['nombre']) ?>?
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
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                        <input type="password" minlength="6" maxlength="64" name="clave" class="form-control" id="claveAgregar" placeholder="Clave" required />
                    </div>
                    <div class="mb-3">
                        <label for="clave2Agregar" class="form-label">Repetir Clave</label>
                        <input type="password" minlength="6" maxlength="64" name="clave2" class="form-control" id="clave2Agregar" placeholder="Repetir Clave" required />
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="pregunta_1" class="form-label">Pregunta de seguridad 1</label>
                        <select class="form-select" name="pregunta_s_1" id="pregunta_1" required>
                            <option value="" disabled selected>Seleccione una pregunta de seguridad</option>
                            <?php foreach ($preguntas_seguridad as $pregunta): ?>
                                <option value="<?= $pregunta['id'] ?>"><?= htmlspecialchars($pregunta['pregunta']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <div class="mb-3 mt-3">
                        <label for="respuesta1Agregar" class="form-label">Respuesta 1</label>
                        <input type="text" maxlength="20" pattern="[a-z0-9\s]{3,}" title="Ingrese solo letras, números o espacios, entre 3 y 20 caracteres" name="respuesta_s_1" class="form-control" id="respuesta1Agregar" placeholder="Respuesta" required />
                    </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="pregunta_2" class="form-label">Pregunta de seguridad 2</label>
                        <select class="form-select" name="pregunta_s_2" id="pregunta_2" required>
                            <option value="" disabled selected>Seleccione una pregunta de seguridad</option>
                            <?php foreach ($preguntas_seguridad as $pregunta): ?>
                                <option value="<?= $pregunta['id'] ?>"><?= htmlspecialchars($pregunta['pregunta']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <div class="mb-3 mt-3">
                        <label for="respuesta2Agregar" class="form-label">Respuesta 2</label>
                        <input type="text" maxlength="20" pattern="[a-z0-9\s]{3,}" title="Ingrese solo letras, números o espacios, entre 3 y 20 caracteres" name="respuesta_s_2" class="form-control" id="respuesta2Agregar" placeholder="Respuesta" required />
                    </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="pregunta_3" class="form-label">Pregunta de seguridad 3</label>
                        <select class="form-select" name="pregunta_s_3" id="pregunta_3" required>
                            <option value="" disabled selected>Seleccione una pregunta de seguridad</option>
                            <?php foreach ($preguntas_seguridad as $pregunta): ?>
                                <option value="<?= $pregunta['id'] ?>"><?= htmlspecialchars($pregunta['pregunta']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <div class="mb-3 mt-3">
                        <label for="respuesta3Agregar" class="form-label">Respuesta 3</label>
                        <input type="text" maxlength="20" pattern="[a-z0-9\s]{3,}" title="Ingrese solo texto en minuscula, entre 3 y 20 caracteres" name="respuesta_s_3" class="form-control" id="respuesta3Agregar" placeholder="Respuesta" required />
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

    <!-- Modal Agregar Rol -->
    <div class="modal fade" id="modalAgregarRol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="?c=roles&accion=insert" method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Nuevo Rol</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Rol</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej. Vendedor" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción de los permisos del rol"></textarea>
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