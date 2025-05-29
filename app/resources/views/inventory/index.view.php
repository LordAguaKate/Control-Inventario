<?php include LAYOUTS . 'main_head.php';
setHeader($d);

?>

<div class="container inventory-index mt-4">
    <h2>Inventario</h2>
    <a href="/inventory/create" class="btn btn-primary mb-3">Agregar Item</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Proveedor</th>
            </tr>
        </thead>
        <tbody>
            <?php if(isset($data['items']) && is_array($data['items']) && !empty($data['items'])): ?>
                <?php foreach($data['items'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td><?= htmlspecialchars($item['category']) ?></td>
                    <td>$<?= number_format($item['price'], 2) ?></td>
                    <td><?= htmlspecialchars($item['supplier']) ?></td>

                </tr>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay items en el inventario</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include_once LAYOUTS . 'main_foot.php'; ?>
