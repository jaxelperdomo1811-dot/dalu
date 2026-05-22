
<table class="table" id="myTable">
    <thead>
        <tr>
            <th>Proveedor</th>
            <th>Insumo</th>
            <th>Número de Lote</th>
            <th>Fecha de Ingreso</th>
            <th>Fecha de Vencimiento</th>
            <th>Precio de Compra</th>
            <th>Cantidad Entrante</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($entradas)): ?>
            <tr>
                <td colspan="5" class="text-center">No hay entradas registradas para este insumo.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($entradas as $entrada): ?>
            <tr>
                <td><?= $entrada['proveedor'] ?></td>
                <td><?= $entrada['insumo'] ?></td>
                <td><?= $entrada['numero_de_lote'] ?></td>
                <td><?= date('d/m/Y', strtotime($entrada['fecha_ingreso'])) ?></td>
                <td><?= date('d/m/Y', strtotime($entrada['fecha_vencimiento'])) ?></td>
                <td><?= number_format($entrada['precio'], 2) ?></td>
                <td><?= $entrada['cantidad_entrante'] ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
