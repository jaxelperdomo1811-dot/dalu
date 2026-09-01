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
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="assets/DataTablet/datatables.css">
    <script src="assets/js/pages/proveedores.js" defer></script>
    <script src="assets/js/js.js" defer></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>

    <title>Proveedores</title>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
</head>

<body>
    <datalist id="prefijos-venezuela">
        <option value="0412">
        <option value="0414">
        <option value="0416">
        <option value="0422">
        <option value="0424">
        <option value="0426">
    </datalist>
    
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div class="container bg-white p-4 rounded shadow-sm">
            <!-- Tabs de Bootstrap principales -->
            <ul class="nav nav-tabs mb-3" id="mainTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-proveedores" data-bs-toggle="tab" data-bs-target="#modulo-proveedores" type="button">Proveedores</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-compras" data-bs-toggle="tab" data-bs-target="#modulo-compras" type="button">Compras de Productos</button>
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
                                            <th scope="col">Apellido</th>
                                            <th scope="col">Razon social</th>
                                            <th scope="col">RIF</th>
                                            <th scope="col">Documento identidad</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Teléfono 2</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Dirección</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proveedores as $p): ?>
                                            <tr>
                                                <td class="align-items-center"><?php echo $p['nombre']; ?></td>
                                                <td class="align-items-center"><?php echo $p['apellido']; ?></td>
                                                <td class="align-items-center"><?php echo $p['razon_social']; ?></td>
                                                <td class="align-items-center"><?php echo $p['rif']; ?></td>
                                                <td class="align-items-center"><?php echo $p['documento_identidad']; ?></td>
                                                <td class="align-items-center"><?php echo $p['telefono']; ?></td>
                                                <td class="align-items-center"><?php echo $p['telefono2']; ?></td>
                                                <td class="align-items-center"><?php echo $p['email']; ?></td>
                                                <td class="align-items-center"><?php echo $p['direccion']; ?></td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditar<?= $p['id'] ?>">Editar</button>
                                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmarEliminar<?= $p['id'] ?>">Desactivar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                
                                <?php foreach ($proveedores as $p): ?>
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
                                                                        <label for="nombre_p_<?= $p['id'] ?>" class="form-label">Nombre</label>
                                                                        <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" id="nombre_p_<?= $p['id'] ?>" placeholder="Nombre" value="<?php echo $p['nombre'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="razon_social_p_<?= $p['id'] ?>" class="form-label">Razon social</label>
                                                                        <input type="text" minlength="5" maxlength="50" title="Ingrese razon social" name="razon_social" class="form-control" id="razon_social_p_<?= $p['id'] ?>" placeholder="Razon social" value="<?php echo $p['razon_social'] ?>" required />
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="rif_p_<?= $p['id'] ?>" class="form-label">RIF</label>
                                                                        <input type="text" minlength="10" maxlength="12" pattern="[VEJPGvejpg]-?[0-9]{8}-?[0-9]" title="Formato RIF: Letra (V,E,J,P,G) seguida de 9 números. Ej: J-12345678-9 o J123456789" name="rif" class="form-control" id="rif_p_<?= $p['id'] ?>" placeholder="Ej: J-12345678-9" value="<?php echo $p['rif'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="telefono_p_<?= $p['id'] ?>" class="form-label">Teléfono</label>
                                                                        <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" id="telefono_p_<?= $p['id'] ?>" name="telefono"  value="<?php echo $p['telefono'] ?>" required />
                                                                        <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="email_p_<?= $p['id'] ?>" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" id="email_p_<?= $p['id'] ?>" placeholder="Email" value="<?php echo $p['email'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="direccion_p_<?= $p['id'] ?>" class="form-label">Dirección</label>
                                                                        <input type="text" minlength="5" maxlength="255" name="direccion" class="form-control" id="direccion_p_<?= $p['id'] ?>" title="Mínimo 5 caracteres" placeholder="Dirección" value="<?php echo $p['direccion'] ?>" required />
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
                            </div>
                        </div>

                        <!-- Proveedores Inactivos -->
                        <div class="tab-pane fade" id="inactivos">
                            <div class="table-responsive">
                                <table id="tablaInactivos" class="table-DT table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Apellido</th>
                                            <th scope="col">Razon social</th>
                                            <th scope="col">RIF</th>
                                            <th scope="col">Documento identidad</th>
                                            <th scope="col">Teléfono</th>
                                            <th scope="col">Teléfono 2</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Dirección</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($proveedoresInactivos as $pIN): ?>
                                            <tr>
                                                <td><?php echo $pIN['nombre']; ?></td>
                                                <td><?php echo $pIN['apellido']; ?></td>
                                                <td><?php echo $pIN['razon_social']; ?></td>
                                                <td><?php echo $pIN['rif']; ?></td>
                                                <td><?php echo $pIN['documento_identidad']; ?></td>
                                                <td><?php echo $pIN['telefono']; ?></td>
                                                <td><?php echo $pIN['telefono2']; ?></td>
                                                <td><?php echo $pIN['email']; ?></td>
                                                <td><?php echo $pIN['direccion']; ?></td>
                                                <td class="text-nowrap">
                                                    <div class="d-flex gap-1">
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarIN<?= $pIN['id'] ?>">Editar</button>
                                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalConfirmarActivar<?= $pIN['id'] ?>">Activar</button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <?php foreach ($proveedoresInactivos as $pIN): ?>
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
                                                                        <label for="nombre_in_<?= $pIN['id'] ?>" class="form-label">Nombre</label>
                                                                        <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" id="nombre_in_<?= $pIN['id'] ?>" placeholder="Nombre" value="<?php echo $pIN['nombre'] ?>" required />
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="razon_social_in_<?= $pIN['id'] ?>" class="form-label">Razon social</label>
                                                                        <input type="text" minlength="5" maxlength="50" title="Ingrese razon social" name="razon_social" class="form-control" id="razon_social_in_<?= $pIN['id'] ?>" placeholder="Razon social" value="<?php echo $pIN['razon_social'] ?>" required />
                                                                    </div>
                                                                </div>
                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="rif_in_<?= $pIN['id'] ?>" class="form-label">RIF</label>
                                                                        <input type="text" minlength="10" maxlength="12" pattern="[VEJPGvejpg]-?[0-9]{8}-?[0-9]" title="Formato RIF: Letra (V,E,J,P,G) seguida de 9 números. Ej: J-12345678-9 o J123456789" name="rif" class="form-control" id="rif_in_<?= $pIN['id'] ?>" placeholder="Ej: J-12345678-9" value="<?php echo $pIN['rif'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-6">
                                                                        <label for="telefono_in_<?= $pIN['id'] ?>" class="form-label">Teléfono</label>
                                                                        <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" id="telefono_in_<?= $pIN['id'] ?>" name="telefono"  value="<?php echo $pIN['telefono'] ?>" required />
                                                                        <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label for="email_in_<?= $pIN['id'] ?>" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" id="email_in_<?= $pIN['id'] ?>" placeholder="Email" value="<?php echo $pIN['email'] ?>" required />
                                                                    </div>
                                                                </div>

                                                                <div class="row mb-3">
                                                                    <div class="col-md-12">
                                                                        <label for="direccion_in_<?= $pIN['id'] ?>" class="form-label">Dirección</label>
                                                                        <input type="text" minlength="5" maxlength="255" name="direccion" class="form-control" id="direccion_in_<?= $pIN['id'] ?>" title="Mínimo 5 caracteres" placeholder="Dirección" value="<?php echo $pIN['direccion'] ?>" required />
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

                <!-- Modulo Compras de Productos -->
                <div class="tab-pane fade" id="modulo-compras">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="titulo text-black">Compras de Productos</h1>
                        <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#entradaModal">
                            + Nueva Compra
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table-DT table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">ID Compra</th>
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
                                        <td colspan="6" class="text-center">No hay compras registradas.</td>
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
                            <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="nombre" class="form-control" placeholder="Nombre" required />
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" minlength="3" maxlength="50" pattern="[A-Za-z0-9\s]{3,}" title="Ingrese solo texto, entre 3 y 50 caracteres" name="apellido" class="form-control" placeholder="Apellido" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="razon_social" class="form-label">Razon social</label>
                            <input type="text" minlength="5" maxlength="50" title="Ingrese razon social" name="razon_social" class="form-control" placeholder="Razon social" required />
                        </div>
                        <div class="col-md-6">
                            <label for="rif" class="form-label">RIF</label>
                            <input type="text" minlength="10" maxlength="12" pattern="[VEJPGvejpg]-?[0-9]{8}-?[0-9]" title="Formato RIF: Letra (V,E,J,P,G) seguida de 9 números. Ej: J-12345678-9 o J123456789" name="rif" class="form-control" placeholder="Ej: J-12345678-9" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" name="telefono" required />
                            <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono2" class="form-label">Teléfono 2 (Opcional)</label>
                            <input type="tel" class="form-control phone-input" inputmode="tel" list="prefijos-venezuela" placeholder="Ej: 04141234567" pattern="^(0(?:412|414|416|422|424|426)\d{7}|\+?[1-9]\d{6,14})$" title="Ingrese un formato nacional (ej. 04141234567) o internacional (ej. +584141234567)" name="telefono2"/>
                            <div class="invalid-feedback phone-error">Número de teléfono inválido.</div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required />
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

    <!-- formulario de registro de compras -->
    <div class="modal fade" id="entradaModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Registrar Compra de Productos</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" method="POST" action="?c=compraProductos&accion=insert" id="entradaForm">
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
                                <div class="col-md-4">
                                    <div class="input-group flex-nowrap">
                                        <select class="form-select producto-select" name="id_producto[]" required>
                                            <option value="" disabled selected>Seleccione un producto</option>
                                            <?php if(isset($productosDisponibles)) { foreach ($productosDisponibles as $prod): ?>
                                                <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?></option>
                                            <?php endforeach; } ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary btn-add-producto" title="Nuevo Producto">+</button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group flex-nowrap">
                                        <select class="form-select variante-select" name="id_variante[]" required>
                                            <option value="" disabled selected>Seleccione variante</option>
                                        </select>
                                        <button type="button" class="btn btn-outline-secondary btn-add-variante" title="Nueva Variante">+</button>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" min="1" required>
                                </div>
                                <div class="col-md-2">
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
                            <button type='submit' class='btn btn-primary'>Guardar Compra</button>
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
                    <h5 class="modal-title">Detalles de la Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidoEntradasInsumo">
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Producto Rápido -->
    <div class="modal fade" id="modalAgregarProductoRapido" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formProductoRapido">
                        <input type="hidden" name="ajax" value="1">
                        <div class="mb-3">
                            <label class="form-label">Categoría *</label>
                            <select class="form-select" name="id_categoria" id="pr_categoria" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <?php if(isset($categorias)) { foreach($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; } ?>
                            </select>
                            <div id="pr_dynamic_attributes" class="mt-2"></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Código</label>
                                <input type="text" class="form-control" name="codigo_producto">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Precio Compra</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="precio_compra">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Precio Venta</label>
                                <input type="number" step="0.01" min="0" class="form-control" name="precio_venta">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Descripción</label>
                                <input type="text" class="form-control" name="descripcion">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarProductoRapido">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Agregar Variante Rápida -->
    <div class="modal fade" id="modalAgregarVarianteRapida" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Variante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formVarianteRapida">
                        <input type="hidden" name="id_producto" id="vr_id_producto">
                        <input type="hidden" name="ajax" value="1">
                        <div class="mb-3">
                            <label class="form-label">Código</label>
                            <input type="text" class="form-control" name="codigo_producto" placeholder="Ej. 1234">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nombre de Variante *</label>
                            <input type="text" class="form-control" name="nombre_variante" required>
                        </div>
                        <div id="dynamicVariantFields"></div>
                        <div class="mb-3">
                            <label class="form-label">Stock Inicial *</label>
                            <input type="number" class="form-control" name="stock" value="0" min="0" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarVarianteRapida">Guardar</button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>