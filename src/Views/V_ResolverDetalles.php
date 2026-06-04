<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="assets/js/libs/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <title>Resolver Detalles Vagos - Pedido #<?= htmlspecialchars($pedido['id']) ?></title>
    <style>
        .resuelto { background-color: #d1e7dd !important; }
        .ignorado { background-color: #e2e3e5 !important; }
    </style>
    <link rel="stylesheet" href="assets/css/libs/select2.min.css">
    <link rel="stylesheet" href="assets/css/libs/select2-bootstrap-5-theme.min.css">
    <script src="assets/js/libs/select2.min.js" defer></script>
    <script src="assets/js/pages/resolver_detalles.js" defer></script>
</head>
<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    
    <div class="container mt-4">
        <h2 class="mb-4">Resolver Detalles - Pedido #<?= htmlspecialchars($pedido['id']) ?> (<?= htmlspecialchars($pedido['nombre_proveedor']) ?>)</h2>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <p>Los siguientes productos de este pedido no están vinculados al inventario. Para crear la entrada, debe resolverlos todos.</p>
        
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaDetalles">
                <thead class="table-dark">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre / Descripción</th>
                        <th>Cantidad</th>
                        <th>Precio U.</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pedido['detalles_pendientes'] as $det): ?>
                    <tr id="row-<?= $det['id'] ?>">
                        <td style="width: 80px;">
                            <?php if(!empty($det['imagen'])): ?>
                                <img src="<?= htmlspecialchars($det['imagen']) ?>" alt="Img" style="width: 100%; object-fit: contain;">
                            <?php else: ?>
                                <span>No img</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($det['nombre_producto'] ?: 'Sin nombre') ?></strong>
                            <?php if(!empty($det['link'])): ?>
                                <br><a href="<?= htmlspecialchars($det['link']) ?>" target="_blank" class="small">Ver en tienda original</a>
                            <?php endif; ?>
                            <br><small><?= htmlspecialchars($det['descripcion_producto']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($det['cantidad']) ?></td>
                        <td><?= htmlspecialchars($det['precio_unitario'] ?? '0.00') ?></td>
                        <td class="acciones-td">
                            <button class="btn btn-sm btn-primary btn-vincular mb-1 w-100" data-id="<?= $det['id'] ?>" data-bs-toggle="modal" data-bs-target="#modalVincular">Vincular existente</button>
                            <button class="btn btn-sm btn-success btn-crear mb-1 w-100" data-id="<?= $det['id'] ?>" data-nombre="<?= htmlspecialchars($det['nombre_producto']) ?>" data-precio="<?= htmlspecialchars($det['precio_unitario']) ?>" data-bs-toggle="modal" data-bs-target="#modalCrear">Crear nuevo</button>
                            <button class="btn btn-sm btn-secondary btn-ignorar mb-1 w-100" data-id="<?= $det['id'] ?>">Ignorar</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-4">
            <a href="?c=pedidos&accion=view" class="btn btn-outline-secondary me-2">Volver</a>
            <form action="?c=entradas&accion=crearDesdePedido" method="POST" class="d-inline" id="formFinalizar">
                <input type="hidden" name="pedido_id" value="<?= $pedido['id'] ?>">
                <button type="button" class="btn btn-success" id="btnFinalizar" onclick="checkFinalizar()">Finalizar y Crear Entrada</button>
            </form>
        </div>
    </div>

    <!-- Modal Vincular -->
    <div class="modal fade" id="modalVincular" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vincular a producto existente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="vincular_detalle_id">
                    <div class="mb-3">
                        <label class="form-label">Seleccione el producto</label>
                        <select class="form-select" id="vincular_id_producto">
                            <option value="">-- Seleccionar --</option>
                            <?php foreach($productos as $prod): ?>
                                <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?> (ID: <?= $prod['id'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarVinculo">Vincular</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalCrear" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formCrearProducto">
                        <input type="hidden" id="crear_detalle_id" name="detalle_id">
                        
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="crear_nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" required>
                                <option value="">-- Seleccionar --</option>
                                <?php foreach($categorias as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Precio Venta ($)</label>
                                <input type="number" step="0.01" class="form-control" name="precio_venta" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">Precio Compra ($)</label>
                                <input type="number" step="0.01" class="form-control" id="crear_precio_compra" name="precio_compra" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Marca (Opcional)</label>
                            <input type="text" class="form-control" name="marca">
                        </div>
                        
                        <!-- Atributos Variante -->
                        <div class="border p-2 mt-2 rounded">
                            <h6>Atributos de Variante (Opcional)</h6>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm mb-1 attr-key" placeholder="Ej: Talla">
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm mb-1 attr-val" placeholder="Ej: M">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm mb-1 attr-key" placeholder="Ej: Color">
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm mb-1 attr-val" placeholder="Ej: Azul">
                                </div>
                            </div>
                            <input type="hidden" name="atributos" id="hidden_atributos">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarNuevo">Crear Producto</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Vincular
            $('.btn-vincular').click(function() {
                $('#vincular_detalle_id').val($(this).data('id'));
                $('#vincular_id_producto').val('');
            });

            $('#btnGuardarVinculo').click(function() {
                const detId = $('#vincular_detalle_id').val();
                const prodId = $('#vincular_id_producto').val();
                if(!prodId) return alert("Seleccione un producto");
                
                $.post('?c=pedidos&accion=vincularDetalleAjax', { detalle_id: detId, id_producto: prodId }, function(res) {
                    if(res.success) {
                        marcarResuelto(detId, 'Vinculado a ID: ' + prodId);
                        $('#modalVincular').modal('hide');
                    } else {
                        alert(res.error || 'Error al vincular');
                    }
                });
            });

            // Crear
            $('.btn-crear').click(function() {
                $('#crear_detalle_id').val($(this).data('id'));
                $('#crear_nombre').val($(this).data('nombre'));
                $('#crear_precio_compra').val($(this).data('precio'));
            });

            $('#btnGuardarNuevo').click(function() {
                if(!$('#formCrearProducto')[0].checkValidity()) {
                    $('#formCrearProducto')[0].reportValidity();
                    return;
                }
                
                // Preparar atributos JSON
                let attrs = {};
                $('.attr-key').each(function(idx, el) {
                    let k = $(el).val().trim();
                    let v = $('.attr-val').eq(idx).val().trim();
                    if(k && v) attrs[k] = v;
                });
                $('#hidden_atributos').val(JSON.stringify(attrs));
                
                let data = $('#formCrearProducto').serialize();
                $.post('?c=pedidos&accion=crearProductoDesdeDetalleAjax', data, function(res) {
                    if(res.success) {
                        marcarResuelto($('#crear_detalle_id').val(), 'Nuevo producto creado');
                        $('#modalCrear').modal('hide');
                        $('#formCrearProducto')[0].reset();
                    } else {
                        alert(res.error || 'Error al crear producto');
                    }
                });
            });

            // Ignorar
            $('.btn-ignorar').click(function() {
                if(!confirm("¿Seguro que desea ignorar este producto? No ingresará al inventario.")) return;
                const detId = $(this).data('id');
                $.post('?c=pedidos&accion=ignorarDetalleAjax', { detalle_id: detId }, function(res) {
                    if(res.success) {
                        marcarIgnorado(detId);
                    } else {
                        alert(res.error || 'Error al ignorar');
                    }
                });
            });
        });

        function marcarResuelto(detId, msg) {
            let row = $('#row-' + detId);
            row.addClass('resuelto');
            row.find('.acciones-td').html('<span class="badge bg-success">' + msg + '</span>');
        }

        function marcarIgnorado(detId) {
            let row = $('#row-' + detId);
            row.addClass('ignorado');
            row.find('.acciones-td').html('<span class="badge bg-secondary">Ignorado</span>');
        }

        function checkFinalizar() {
            // Verificar si hay botones de acciones activos (no resueltos)
            if($('.acciones-td .btn').length > 0) {
                alert('Debe resolver todos los productos pendientes (vincular, crear o ignorar) antes de finalizar.');
                return;
            }
            if(confirm("¿Confirmar la creación de la Entrada de Inventario?")) {
                $('#formFinalizar').submit();
            }
        }
    </script>
</body>
</html>
