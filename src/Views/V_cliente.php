<?php
$tituloPagina = "Clientes";
$extraCss = [
    "assets/css/css.css",
    "assets/css/tabla.css",
    "assets/DataTablet/datatables.css",
    "assets/css/libs/select2.min.css",
    "assets/css/libs/select2-bootstrap-5-theme.min.css"
];
$extraJs = [
    "assets/js/cliente.js",
    "assets/js/pages/clientes.js",
    "assets/DataTablet/datatables.min.js",
    "assets/DataTablet/tabla.js",
    "assets/js/libs/select2.min.js"
];
require_once __DIR__ . "/../Views/layout/head.php";
?>

<body>
    <datalist id="prefijos-venezuela">
        <option value="0412">
        <option value="0414">
        <option value="0416">
        <option value="0422">
        <option value="0424">
        <option value="0426">
    </datalist>

    <?php
        $clientes = $clientes ?? $clientesActivos ?? [];
        $clientesInactivos = $clientesInactivos ?? [];
    ?>

    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="titulo text-black">Clientes</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAgregar">+ Nuevo Cliente</button>
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
                                    <th scope="col">Cedula</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Apellido</th>
                                    <th scope="col">Correo</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Fecha Registro</th>
                                    <th scope="col">Acción</th>
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
                                        <td class="text-nowrap">
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $c['id'] ?>">Editar</button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminar<?= $c['id'] ?>">Desactivar</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <?php foreach ($clientes as $c): ?>
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
                                                                <label for="nombre_<?= $c['id'] ?>" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre_<?= $c['id'] ?>" placeholder="Nombre" value="<?php echo $c['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido_<?= $c['id'] ?>" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido_<?= $c['id'] ?>" placeholder="Apellido" value="<?php echo $c['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono_<?= $c['id'] ?>" class="form-label">Teléfono</label>
                                                                <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" id="telefono_<?= $c['id'] ?>" name="telefono"  value="<?php echo $c['telefono'] ?>" required />
                                                                <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo_<?= $c['id'] ?>" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo_<?= $c['id'] ?>" name="correo" placeholder="Correo" value="<?php echo $c['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion_<?= $c['id'] ?>" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion_<?= $c['id'] ?>" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $c['direccion'] ?>" required />
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
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Dirección</th>
                                    <th scope="col">Fecha Registro</th>
                                    <th scope="col">Acción</th>
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
                                        <td class="text-nowrap">
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $cIN['id'] ?>">Editar</button>
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivar<?= $cIN['id'] ?>">Activar</button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php foreach ($clientesInactivos as $cIN): ?>
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
                                                                <label for="nombre_in_<?= $cIN['id'] ?>" class="form-label">Nombre</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre_in_<?= $cIN['id'] ?>" placeholder="Nombre" value="<?php echo $cIN['nombre'] ?>" required />
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="apellido_in_<?= $cIN['id'] ?>" class="form-label">Apellido</label>
                                                                <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido_in_<?= $cIN['id'] ?>" placeholder="Apellido" value="<?php echo $cIN['apellido'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-6">
                                                                <label for="telefono_in_<?= $cIN['id'] ?>" class="form-label">Teléfono</label>
                                                                <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" id="telefono_in_<?= $cIN['id'] ?>" name="telefono"  value="<?php echo $cIN['telefono'] ?>" required />
                                                                <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label for="correo_in_<?= $cIN['id'] ?>" class="form-label">Correo</label>
                                                                <input type="email" class="form-control" id="correo_in_<?= $cIN['id'] ?>" name="correo" placeholder="Correo" value="<?php echo $cIN['correo'] ?>" required />
                                                            </div>
                                                        </div>

                                                        <div class="row mb-3">
                                                            <div class="col-md-12">
                                                                <label for="direccion_in_<?= $cIN['id'] ?>" class="form-label">Dirección</label>
                                                                <input type="text" minlength="5" maxlength="25" name="direccion" class="form-control" id="direccion_in_<?= $cIN['id'] ?>" title="Entre 5 y 25 caracteres" placeholder="Dirección" value="<?php echo $cIN['direccion'] ?>" required />
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
                    </div>
                </div>
            </div>
        </div>
    </main>

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
                        <select class="form-select no-select2" name="tipo_persona" id="tipo_persona" required>
                            <option value="" disabled selected>Seleccione un tipo de persona</option>
                            <option value="V-">Natural (V)</option>
                            <option value="J-">Jurídica (J)</option>
                            <option value="E-">Extranjera (E)</option>
                        </select>
                    </div>
                    <div class="col-md-7">
                        <label for="cedula" class="form-label">Documento</label>
                        <input type="number" class="form-control" pattern="[0-9]{6,10}" minlength="6"    maxlength="10" title="Solo números, entre 6 y 10 caracteres" name="cedula" id="cedula" placeholder="Número de Documento" required />
                        <div id="mensaje-cedula" style="color: red; margin-top: 5px;"></div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div id="input-nombre-container" class="col-md-6">
                        <label id="label-nombre" for="nombre" class="form-label">Nombre</label>
                        <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control" id="nombre" placeholder="Nombre" required />
                    </div>
                    <div class="col-md-6" id="input-apellido-container">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" minlength="3" maxlength="20" pattern="[A-Za-z\s]{3,}" title="Ingrese solo texto, entre 3 y 20 caracteres" name="apellido" class="form-control" id="apellido" placeholder="Apellido" required />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="telefono-agregar" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" id="telefono-agregar" name="telefono"  required />
                        <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
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

</body>
</html>