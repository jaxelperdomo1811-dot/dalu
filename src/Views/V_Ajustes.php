<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/css.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/bootstrap.min.css">
    <link rel="icon" href="assets/img/dalulisto.png">
    <script src="assets/js/js.js" defer></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <title>Ajustes y Configuración</title>
</head>
<body>
    <?php require_once __DIR__ . "/../Views/layout/header.php"; ?>
    <main>
        <div class="container bg-white p-4 rounded shadow-sm mt-3">
            <h1 class="titulo text-black mb-4">Ajustes y Configuración</h1>
            
            <div class="row">
                <!-- Tasas de Cambio -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0" style="background-color: #f8f9fa;">
                        <div class="card-header bg-primary text-white rounded-top border-0">
                            <h5 class="mb-0"><i class="fa fa-money-bill-wave me-2"></i>Tasas de Cambio</h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- BCV -->
                            <form action="?c=Ajustes&accion=guardarTasa" method="POST" class="mb-4 p-3 bg-white shadow-sm rounded">
                                <h6 class="fw-bold mb-3">Tasa BCV Oficial</h6>
                                <input type="hidden" name="nombre" value="BCV">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light fw-bold">Bs</span>
                                    <input type="number" step="0.0001" class="form-control" name="valor" value="<?= htmlspecialchars($tasaBcv['valor'] ?? '0.00') ?>" required>
                                    <button class="btn btn-success px-4" type="submit">Guardar</button>
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    Última actualización: <?= htmlspecialchars($tasaBcv['fecha_actualizacion'] ?? 'No disponible') ?>
                                </div>
                            </form>

                            <!-- Zelle -->
                            <form action="?c=Ajustes&accion=guardarTasa" method="POST" class="p-3 bg-white shadow-sm rounded">
                                <h6 class="fw-bold mb-3">Tasa Zelle (Tasa Especial)</h6>
                                <input type="hidden" name="nombre" value="Zelle">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-light fw-bold">Bs</span>
                                    <input type="number" step="0.0001" class="form-control" name="valor" value="<?= htmlspecialchars($tasaZelle['valor'] ?? '0.00') ?>" required>
                                    <button class="btn btn-success px-4" type="submit">Guardar</button>
                                </div>
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    Última actualización: <?= htmlspecialchars($tasaZelle['fecha_actualizacion'] ?? 'No disponible') ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Configuración Global -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100 border-0" style="background-color: #f8f9fa;">
                        <div class="card-header bg-warning text-dark rounded-top border-0">
                            <h5 class="mb-0"><i class="fa fa-percent me-2"></i>Parámetros Globales</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="?c=Ajustes&accion=guardarAjustes" method="POST" class="bg-white shadow-sm rounded p-4">
                                <?php
                                // Mapeo de claves a nombres legibles
                                $nombresLegibles = [
                                    'porcentaje_envio' => 'Porcentaje de Envío',
                                    'porcentaje_ganancia' => 'Porcentaje de Ganancia'
                                ];

                                foreach ($configuraciones as $clave => $valor): 
                                    $nombreInput = $nombresLegibles[$clave] ?? ucfirst(str_replace('_', ' ', $clave));
                                ?>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark mb-1"><?= htmlspecialchars($nombreInput) ?></label>
                                        <p class="text-muted mb-2" style="font-size: 0.8rem;">Modifica el multiplicador automático (porcentaje) para el cálculo de ventas de los productos.</p>
                                        <div class="input-group">
                                            <input type="number" step="0.01" class="form-control" name="ajustes[<?= htmlspecialchars($clave) ?>]" value="<?= htmlspecialchars($valor) ?>" required>
                                            <span class="input-group-text fw-bold text-secondary">%</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <button type="submit" class="btn btn-warning w-100 mt-2 py-2 fw-bold shadow-sm">Guardar Todos los Ajustes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
