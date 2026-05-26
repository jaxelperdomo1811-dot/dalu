<!DOCTYPE html>
<html lang="es">

<head>
    <title>Dalu Boutique - Facturación</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>

    <main>
        <div>
            <h1 class="titulo">Facturación</h1>
        </div>
        <hr>

        <div class="container mt-5">
            <div class="card shadow-sm border-0 rounded-4" style="max-width: 800px; margin: 0 auto;">
                <div class="card-header text-white rounded-top-4" style="background-color: #1a1a1a;">
                    <h5 class="mb-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-receipt me-2" viewBox="0 0 16 16">
                          <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z"/>
                          <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                        Registrar Factura
                    </h5>
                </div>

                <div class="card-body bg-light-subtle px-4 py-4">
                    <form id="facturaForm" action="index.php?c=facturacion&accion=guardar" method="POST">

                        <!-- Cliente -->
                        <div class="mb-4 position-relative">
                            <label for="cliente" class="form-label fw-semibold">Cliente</label>
                            <input type="text" id="cliente" name="cliente" class="form-control rounded-pill" autocomplete="off" placeholder="Ingrese nombre o cédula del cliente..." required>
                            <input type="hidden" id="id_cliente" name="id_cliente">
                            <div id="clienteList" class="list-group position-absolute shadow-sm" style="z-index:1000; width: 100%; display:none;"></div>
                        </div>

                        <div class="container p-0">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Select de Productos -->
                                    <div class="mb-4">
                                        <label for="selectProducto" class="form-label fw-semibold">Producto</label>
                                        <select id="selectProducto" name="id_producto" class="form-select rounded-pill" required>
                                            <option value="">-- Selecciona un producto --</option>
                                            <?php if(isset($productos) && is_array($productos)): ?>
                                                <?php foreach ($productos as $producto): ?>
                                                    <option value="<?= htmlspecialchars($producto['id']) ?>" data-precio="<?= htmlspecialchars($producto['precio']) ?>">
                                                        <?= htmlspecialchars($producto['nombre']) ?> - $<?= htmlspecialchars($producto['precio']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="" disabled>No hay productos disponibles</option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4"> 
                                    <!-- Cantidad -->
                                    <div class="mb-4">
                                        <label for="cantidad" class="form-label fw-semibold">Cantidad</label>
                                        <input type="number" id="cantidad" name="cantidad" class="form-control rounded-pill" min="1" value="1" required>
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <!-- Fecha -->
                        <div class="mb-4">
                            <label for="fecha" class="form-label fw-semibold">Fecha</label>
                            <input type="date" id="fecha" name="fecha" class="form-control rounded-pill" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="container p-0">
                            <div class="row">
                                <div class="col-md-6"> 
                                    <!-- Método de Pago -->
                                    <div class="mb-4">
                                        <label for="metodo_pago" class="form-label fw-semibold">Método de Pago</label>
                                        <select id="metodo_pago" name="metodo_pago" class="form-select rounded-pill" required>
                                            <option value="">Seleccione un método</option>
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta de Débito">Tarjeta de Débito</option>
                                            <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                                            <option value="Transferencia">Transferencia Bancaria</option>
                                            <option value="Pago Móvil">Pago Móvil</option>
                                            <option value="Zelle">Zelle</option>
                                        </select>
                                    </div> 
                                </div>

                                <div class="col-md-6">   
                                    <!-- Referencia -->
                                    <div class="mb-4">
                                        <label for="referencia" class="form-label fw-semibold">Referencia (Opcional)</label>
                                        <input type="text" id="referencia" name="referencia" class="form-control rounded-pill" placeholder="Número de recibo o transferencia">
                                    </div> 
                                </div>
                            </div>
                        </div>

                        <!-- Monto -->
                        <div class="mb-4">
                            <label for="monto_total" class="form-label fw-semibold">Monto Total a Pagar ($)</label>
                            <input type="number" step="0.01" id="monto_total" name="monto_total" class="form-control rounded-pill" readonly required style="background-color: #e9ecef; font-weight: bold; font-size: 1.2rem;">
                        </div>

                        <!-- Botón -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn text-white rounded-pill py-2" style="background-color: #c5a059; border: none; font-weight: bold; transition: 0.3s;" onmouseover="this.style.backgroundColor='#a38347'" onmouseout="this.style.backgroundColor='#c5a059'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bag-check me-2" viewBox="0 0 16 16">
                                  <path fill-rule="evenodd" d="M10.854 8.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                  <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
                                </svg>
                                Registrar Venta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Cálculo automático del monto total basado en producto y cantidad
        $(document).ready(function() {
            function calcularTotal() {
                let precio = parseFloat($('#selectProducto option:selected').data('precio')) || 0;
                let cantidad = parseInt($('#cantidad').val()) || 1;
                let total = precio * cantidad;
                $('#monto_total').val(total.toFixed(2));
            }

            $('#selectProducto, #cantidad').on('change keyup', calcularTotal);
        });
    </script>
</body>
</html>