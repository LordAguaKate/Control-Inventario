<?php 
// Incluir el encabezado principal
include __DIR__ . '/../../layouts/main_head.php';

// Configurar el encabezado de la página
setHeader($d);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Mis Publicaciones</h2>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <div class="row">
            <?php 
            // Asegurarse de que $products sea un array
            $products = is_array($products) ? $products : [];
            foreach ($products as $product): 
                // Asegurarse de que $product sea un array
                $product = (array)$product;
            ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($product['name'] ?? 'Sin nombre') ?></h5>
                            <div class="mb-2">
                                <span class="badge bg-secondary"><?= htmlspecialchars($product['category'] ?? 'Sin categoría') ?></span>
                                <span class="badge bg-success ms-1">$<?= number_format($product['price'] ?? 0, 2) ?></span>
                            </div>
                            <p class="card-text"><?= nl2br(htmlspecialchars($product['description'] ?? 'Sin descripción')) ?></p>
                            <div class="mt-auto">
                                <p class="mb-1">
                                    <small class="text-muted">
                                        <strong>Disponibles:</strong> <?= htmlspecialchars($product['quantity'] ?? 0) ?>
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        <strong>Proveedor:</strong> <?= htmlspecialchars($product['supplier'] ?? 'No especificado') ?>
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        <strong>Agregado:</strong> <?= htmlspecialchars($product['fecha_formateada'] ?? 'N/A') ?>
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No hay productos disponibles en este momento.
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../layouts/main_footer.php'; ?>
