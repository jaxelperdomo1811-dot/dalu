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
                            <button class="btn btn-sm btn-success btn-crear mb-1 w-100" data-id="<?= $det['id'] ?>" data-nombre="<?= htmlspecialchars($det['nombre_producto']) ?>" data-precio="<?= htmlspecialchars($det['precio_unitario']) ?>" data-bs-toggle="modal" data-bs-target="#modalAgregar">Crear nuevo</button>
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
            <form id="formVincularProducto" class="modal-content" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Vincular a producto existente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="vincular_detalle_id" name="detalle_id">
                    <div class="mb-3">
                        <label class="form-label">Seleccione el producto</label>
                        <select class="form-select" id="vincular_id_producto" name="id_producto">
                            <option value="">-- Seleccionar --</option>
                            <?php foreach($productos as $prod): ?>
                                <option value="<?= $prod['id'] ?>"><?= htmlspecialchars($prod['nombre']) ?> (ID: <?= $prod['id'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div id="vincular_variante_container" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Seleccione la variante</label>
                            <select class="form-select" id="vincular_id_variante" name="id_variante">
                                <option value="">-- Seleccionar variante --</option>
                            </select>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="vincular_nueva_variante">
                            <label class="form-check-label" for="vincular_nueva_variante">
                                O registrar una variante nueva
                            </label>
                        </div>
                        
                        <div id="nueva_variante_form" class="border p-3 rounded" style="display: none; background: #f8f9fa;">
                            <h6>Nueva Variante</h6>
                            <input type="hidden" id="vincular_cat_nombre">
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Nombre de variante</label>
                                    <input type="text" class="form-control form-control-sm" name="nombre_variante" id="vincular_nombre_variante" placeholder="Ej. Principal">
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Stock recibido</label>
                                    <input type="number" min="0" class="form-control form-control-sm" name="stock" id="vincular_stock">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Precio adicional</label>
                                    <input type="number" step="0.01" min="0" class="form-control form-control-sm" name="precio_adicional" value="0">
                                </div>
                                <div class="col-md-12 mb-2">
                                    <label class="form-label">Imagen (Opcional)</label>
                                    <input type="file" accept="image/*" class="form-control form-control-sm" name="imagen_variante">
                                </div>
                            </div>
                            <!-- Atributos dinámicos -->
                            <div id="vincular_atributos_dinamicos" class="row mt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarVinculo">Vincular</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Crear -->
    <div class="modal fade" id="modalAgregar" tabindex="-1">
        <div class="modal-dialog">
            <form id="formCrearProducto" class="modal-content" enctype="multipart/form-data">
                <input type="hidden" id="crear_detalle_id" name="detalle_id">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="categoria_input" class="form-label">Categoría</label>
                            <select class="form-select" name="id_categoria" id="categoria_input" required>
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <?php foreach($categorias as $categoria): ?>
                                    <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <!-- Contenedor para campos dinámicos según categoría -->
                            <div id="dynamic-attributes" class="mt-3"></div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre_input" class="form-label">Nombre</label>
                            <input type="text" minlength="3" maxlength="20"
                                pattern="[A-Za-z\s]{3,}"
                                title="Ingrese solo texto, entre 3 y 20 caracteres" name="nombre" class="form-control"
                                id="crear_nombre" placeholder="Nombre" required />
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio Venta</label>
                            <input type="number" step="0.01" min="0" name="precio_venta" class="form-control"
                                id="precio" placeholder="Precio Venta" required />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="precio_compra" class="form-label">Precio Compra</label>
                            <input type="number" step="0.01" min="0" name="precio_compra" class="form-control"
                                id="crear_precio_compra" placeholder="Precio Compra" />
                        </div>
                        <div class="col-md-6">
                            <label for="descripcion_input" class="form-label">Descripción</label>
                            <input type="text" minlength="5" maxlength="25" name="descripcion" class="form-control"
                                id="descripcion_input" title="Entre 5 y 25 caracteres" placeholder="Descripción" required />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnGuardarNuevo">Crear Producto</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Lógica para renderizar atributos dinámicos basada en categoryName
            function renderVariantExtras(categoryName) {
                const n = (categoryName || '').toLowerCase();
                const field = (label, name, type = 'text') => `
                    <div class="col-md-6 mb-2">
                        <label class="form-label">${label}</label>
                        <input type="${type}" name="${name}" class="form-control form-control-sm" placeholder="${label}" />
                    </div>`;

                if (n.includes('ropa') || n.includes('camisa') || n.includes('vestido') || n.includes('calzado') || n.includes('zapato') || n.includes('cartera') || n.includes('bisuter')) {
                    return field('Talla', 'talla') + field('Color', 'color');
                }
                if (n.includes('perfume') || n.includes('fragancia') || n.includes('colonia')) {
                    return field('Volumen (ml)', 'volumen_ml', 'number') + field('Fragancia', 'fragancia');
                }
                if (n.includes('cosmet') || n.includes('maquill') || n.includes('piel') || n.includes('spf')) {
                    return field('SPF', 'spf', 'number') + field('Tipo de piel', 'tipo_piel') + field('Volumen (ml)', 'volumen_ml', 'number') + field('Fragancia', 'fragancia');
                }
                return '';
            }

            // Vincular
            $('.btn-vincular').click(function() {
                $('#vincular_detalle_id').val($(this).data('id'));
                $('#vincular_id_producto').val('');
                $('#vincular_variante_container').hide();
                $('#vincular_nueva_variante').prop('checked', false);
                $('#nueva_variante_form').hide();
                // Pre-llenar stock sugerido
                let qty = $(this).closest('tr').find('td:nth-child(3)').text();
                $('#vincular_stock').val(qty);
            });

            $('#vincular_id_producto').change(function() {
                const prodId = $(this).val();
                if (!prodId) {
                    $('#vincular_variante_container').hide();
                    return;
                }
                
                // Fetch variantes del producto
                $.get('?c=productos&accion=getProductoJson&id=' + prodId, function(res) {
                    if (res && res.id) {
                        $('#vincular_cat_nombre').val(res.categoria_nombre);
                        $('#vincular_atributos_dinamicos').html(renderVariantExtras(res.categoria_nombre));
                        
                        let selectVar = $('#vincular_id_variante');
                        selectVar.empty();
                        if (res.variantes && res.variantes.length > 0) {
                            res.variantes.forEach(v => {
                                let attrs = v.atributos ? JSON.stringify(v.atributos) : '';
                                selectVar.append(`<option value="${v.id}">${v.nombre_variante} - Stock: ${v.stock} ${attrs}</option>`);
                            });
                        } else {
                            selectVar.append('<option value="">Sin variantes registradas</option>');
                        }
                        $('#vincular_variante_container').show();
                    }
                });
            });

            $('#vincular_nueva_variante').change(function() {
                if ($(this).is(':checked')) {
                    $('#nueva_variante_form').show();
                    $('#vincular_id_variante').prop('disabled', true);
                    $('#vincular_nombre_variante').prop('required', true);
                    $('#vincular_stock').prop('required', true);
                } else {
                    $('#nueva_variante_form').hide();
                    $('#vincular_id_variante').prop('disabled', false);
                    $('#vincular_nombre_variante').prop('required', false);
                    $('#vincular_stock').prop('required', false);
                }
            });

            $('#btnGuardarVinculo').click(function() {
                const detId = $('#vincular_detalle_id').val();
                const prodId = $('#vincular_id_producto').val();
                if(!prodId) return alert("Seleccione un producto");
                
                const isNewVariant = $('#vincular_nueva_variante').is(':checked');
                
                if (isNewVariant) {
                    // Validar form required
                    if(!$('#formVincularProducto')[0].checkValidity()) {
                        $('#formVincularProducto')[0].reportValidity();
                        return;
                    }
                    
                    let formData = new FormData($('#formVincularProducto')[0]);
                    
                    $.ajax({
                        url: '?c=pedidos&accion=vincularConNuevaVarianteAjax',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(res) {
                            if(res.success) {
                                marcarResuelto(detId, 'Nueva variante vinculada al ID: ' + prodId);
                                $('#modalVincular').modal('hide');
                            } else {
                                alert(res.error || 'Error al vincular con nueva variante');
                            }
                        }
                    });
                    
                } else {
                    // Vincular normalmente (puede incluir variante seleccionada o nula)
                    const varId = $('#vincular_id_variante').val();
                    $.post('?c=pedidos&accion=vincularDetalleAjax', { detalle_id: detId, id_producto: prodId, id_variante: varId }, function(res) {
                        if(res.success) {
                            marcarResuelto(detId, 'Vinculado a ID: ' + prodId);
                            $('#modalVincular').modal('hide');
                        } else {
                            alert(res.error || 'Error al vincular');
                        }
                    });
                }
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
                
                let formData = new FormData($('#formCrearProducto')[0]);
                
                $.ajax({
                    url: '?c=pedidos&accion=crearProductoDesdeDetalleAjax',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if(res.success) {
                            marcarResuelto($('#crear_detalle_id').val(), 'Nuevo producto creado');
                            $('#modalAgregar').modal('hide');
                            $('#formCrearProducto')[0].reset();
                            $('#dynamic-attributes').empty(); // limpiar
                        } else {
                            alert(res.error || 'Error al crear producto');
                        }
                    },
                    error: function() {
                        alert('Error al comunicarse con el servidor');
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
    <!-- Exponer categorías a JS y cargar script de productos -->
    <?php
    $ajustesModel = new \Lenovo\Dalu\Models\Ajustes();
    $tasaModel = new \Lenovo\Dalu\Models\Tasa();
    
    $pct_envio = $ajustesModel->get('porcentaje_envio') ?? 20;
    $pct_ganancia = $ajustesModel->get('porcentaje_ganancia') ?? 30;
    
    $factor_envio = 1 + ($pct_envio / 100);
    $factor_ganancia = 1 + ($pct_ganancia / 100);
    
    $tasa_bcv_data = $tasaModel->getLatest('BCV');
    $tasa_zelle_data = $tasaModel->getLatest('Zelle'); 
    
    $tasa_bcv = $tasa_bcv_data ? floatval($tasa_bcv_data['valor']) : 1;
    $tasa_zelle = $tasa_zelle_data ? floatval($tasa_zelle_data['valor']) : $tasa_bcv;
    if ($tasa_bcv <= 0) $tasa_bcv = 1;
    $ratio_tasa = $tasa_zelle / $tasa_bcv;
    ?>
    <script>
        window.productosCategories = <?php echo json_encode($categorias ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]'; ?>;
        window.factorEnvio = <?= json_encode($factor_envio) ?>;
        window.factorGanancia = <?= json_encode($factor_ganancia) ?>;
        window.ratioTasa = <?= json_encode($ratio_tasa) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Delegación de eventos para calcular el precio de venta cuando se edite precio_compra
            document.body.addEventListener('input', function(e) {
                if (e.target && e.target.name === 'precio_compra') {
                    const container = e.target.closest('form');
                    if (container) {
                        const inputVenta = container.querySelector('input[name="precio_venta"]');
                        if (inputVenta) {
                            const val = parseFloat(e.target.value);
                            if (!isNaN(val) && val > 0) {
                                const calculado = (val * window.factorEnvio * window.factorGanancia) * window.ratioTasa;
                                inputVenta.value = calculado.toFixed(2);
                            }
                        }
                    }
                }
            });
        });
    </script>
    <script src="assets/js/pages/productos.js" defer></script>
</body>
</html>
