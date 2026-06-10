<?php
if (!isset($producto) || !$producto) {
    echo '<div class="alert alert-danger">Producto no encontrado.</div>';
    return;
}

$variantes = $producto['variantes'] ?? [];
?>

<?php if (empty($variantes)): ?>
    <div class="alert alert-info mb-0">Este producto no tiene variantes registradas.</div>
<?php else: ?>
    <div class="table-responsive mb-0">
        <table class="table table-bordered table-striped table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 70px;">Imagen</th>
                    <th>Nombre de Variante</th>
                    <th>Atributos</th>
                    <th class="text-end">Precio Adicional</th>
                    <th class="text-center">Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($variantes as $v): ?>
                    <tr>
                        <td class="text-center">
                            <?php if (!empty($v['imagen_variante'])): ?>
                                <img src="<?= htmlspecialchars($v['imagen_variante']) ?>" onerror="this.src='assets/img/products/default.jpeg'" alt="Variante" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted" style="font-size: 0.8em;">N/A</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?= htmlspecialchars($v['nombre_variante']) ?></td>
                        <td>
                            <?php
                            $atributos = $v['atributos'];
                            if (is_string($atributos)) {
                                $atributos = json_decode($atributos, true);
                            }
                            if (!empty($atributos) && is_array($atributos)) {
                                foreach ($atributos as $key => $val) {
                                    echo '<span class="badge bg-secondary me-1 mb-1">' . htmlspecialchars(ucfirst($key)) . ': ' . htmlspecialchars($val) . '</span>';
                                }
                            } else {
                                echo '<span class="text-muted" style="font-size: 0.85em;">Ninguno</span>';
                            }
                            ?>
                        </td>
                        <td class="text-end">
                            <?php if ($v['precio_adicional'] > 0): ?>
                                <span class="text-success fw-bold">+<?= number_format($v['precio_adicional'], 2) ?></span>
                            <?php else: ?>
                                <span class="text-muted">0.00</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <?php if ($v['stock'] > 10): ?>
                                <span class="badge bg-success"><?= htmlspecialchars($v['stock']) ?></span>
                            <?php elseif ($v['stock'] > 0): ?>
                                <span class="badge bg-warning text-dark"><?= htmlspecialchars($v['stock']) ?></span>
                            <?php else: ?>
                                <span class="badge bg-danger">Agotado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
