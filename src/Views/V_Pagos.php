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
    
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/DataTablet/datatables.min.js" defer></script>
    <script src="assets/DataTablet/tabla.js" defer></script>
    <script src="assets/sweetalert2@11.js"></script>
    <script src="assets/js/pages/pagos.js" defer></script>
    <title>Gestión de Pagos</title>
</head>
<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="titulo text-black">Gestión de Pagos</h1>
            </div>

            <ul class="nav nav-tabs mb-3" id="pagosTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-debito" data-bs-toggle="tab" data-bs-target="#modulo-debito" type="button">Débito</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-credito" data-bs-toggle="tab" data-bs-target="#modulo-credito" type="button">Crédito</button>
                </li>
            </ul>

            <div class="tab-content">
                <!-- Modulo Debito -->
                <div class="tab-pane fade show active" id="modulo-debito">
                    <div class="table-responsive">
                        <table id="tablaPagosDebito" class="table-DT table table-striped w-100">
                            <thead>
                                <tr>
                                    <th scope="col">Nro Nota</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Total ($)</th>
                                    <th scope="col">Pagos Reg.</th>
                                    <th scope="col">Auditoría</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notasDebito as $nota): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($nota['id_nota']) ?></td>
                                        <td><?= htmlspecialchars($nota['cliente_nombre']) ?></td>
                                        <td><?= number_format((float)$nota['total'], 2) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($nota['total_pagos']) ?></span></td>
                                        <td>
                                            <?php if ($nota['pagos_por_verificar'] > 0): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle"></i> <?= $nota['pagos_por_verificar'] ?> Por Verificar</span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="fa fa-check-circle"></i> Al Día</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-pagos" data-id="<?= $nota['id_nota'] ?>">Detalles</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modulo Credito -->
                <div class="tab-pane fade" id="modulo-credito">
                    <div class="table-responsive">
                        <table id="tablaPagosCredito" class="table-DT table table-striped w-100">
                            <thead>
                                <tr>
                                    <th scope="col">Nro Nota</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Total ($)</th>
                                    <th scope="col">Pagos Reg.</th>
                                    <th scope="col">Auditoría</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notasCredito as $nota): ?>
                                    <tr>
                                        <td>#<?= htmlspecialchars($nota['id_nota']) ?></td>
                                        <td><?= htmlspecialchars($nota['cliente_nombre']) ?></td>
                                        <td><?= number_format((float)$nota['total'], 2) ?></td>
                                        <td><span class="badge bg-secondary"><?= htmlspecialchars($nota['total_pagos']) ?></span></td>
                                        <td>
                                            <?php if ($nota['pagos_por_verificar'] > 0): ?>
                                                <span class="badge bg-warning text-dark"><i class="fa fa-exclamation-triangle"></i> <?= $nota['pagos_por_verificar'] ?> Por Verificar</span>
                                            <?php else: ?>
                                                <span class="badge bg-success"><i class="fa fa-check-circle"></i> Al Día</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info m-1 btn-ver-pagos" data-id="<?= $nota['id_nota'] ?>">Detalles</button>
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

    <!-- Modal para Verificar Pagos Individuales -->
    <div class="modal fade" id="modalVerificarPagos" tabindex="-1" aria-labelledby="modalVerificarPagosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalVerificarPagosLabel">Auditoría de Pagos - Nota #<span id="lblNotaId"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID Pago</th>
                                    <th>Fecha</th>
                                    <th>Método</th>
                                    <th>Referencia</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Auditoría</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyPagosIndividuales">
                            </tbody>
                        </table>
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
