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
    <script src="assets/js/pages/despachos.js" defer></script>
    
    <title>Gestión de Despachos</title>
</head>

<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <?php
        $despachos = $despachos ?? [];
    ?>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1 class="titulo text-black">Gestión de Despachos</h1>
                <!-- El botón de Nuevo Despacho ha sido eliminado. Los despachos se crean desde Notas de Entrega. -->
            </div>

            <div class="table-responsive">
                <table id="tablaDespachos" class="table-DT table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">Nro Despacho</th>
                            <th scope="col">Nota de Entrega</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Total ($)</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($despachos as $d): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($d['numero_despacho']); ?></td>
                                <td>#<?php echo htmlspecialchars($d['id_nota_entrega']); ?></td>
                                <td><?php echo htmlspecialchars($d['cliente_nombre'] . ' ' . $d['cliente_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($d['fecha_despacho']); ?></td>
                                <td><?php echo htmlspecialchars($d['total']); ?></td>
                                <td><?php echo htmlspecialchars($d['estado']); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info m-1 btn-ver-detalles" data-id="<?= $d['id'] ?>">Detalles</button>
                                    
                                    <?php if($d['estado'] === 'pendiente'): ?>
                                    <form action="?c=Despacho&accion=cambiarEstado" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                        <input type="hidden" name="estado" value="enviado">
                                        <button type="submit" class="btn btn-sm btn-warning m-1">Marcar Enviado</button>
                                    </form>
                                    <form action="?c=Despacho&accion=cambiarEstado" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                        <input type="hidden" name="estado" value="cancelado">
                                        <button type="submit" class="btn btn-sm btn-danger m-1" onclick="return confirm('¿Seguro que desea cancelar este despacho?')">Cancelar</button>
                                    </form>
                                    <?php elseif($d['estado'] === 'enviado'): ?>
                                    <form action="?c=Despacho&accion=cambiarEstado" method="POST" class="d-inline">
                                        <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                        <input type="hidden" name="estado" value="entregado">
                                        <button type="submit" class="btn btn-sm btn-success m-1">Marcar Entregado</button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Detalles del Despacho -->
    <div class="modal fade" id="modalDetallesDespacho" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Despacho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyDetalles">
                                <!-- Llenado por JS -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <th id="detalleModalTotal">$0.00</th>
                                </tr>
                            </tfoot>
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
