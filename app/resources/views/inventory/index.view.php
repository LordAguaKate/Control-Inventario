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
                <th>Acciones</th>
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
                    <td>
                        <!-- Botón Editar -->
                        <button 
                            class="btn btn-sm btn-warning" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal<?= $item['id'] ?>"
                        >
                            Editar
                        </button>

                        <!-- Botón Eliminar -->
                        <button 
                            class="btn btn-sm btn-danger" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal<?= $item['id'] ?>"
                        >
                            Eliminar
                        </button>
                    </td>
                </tr>

                <!-- Modal Editar -->
                <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <form action="/inventory/update/<?= $item['id'] ?>" method="POST" class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                          <div class="mb-3">
                              <label>Nombre</label>
                              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($item['name']) ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Cantidad</label>
                              <input type="number" name="quantity" class="form-control" value="<?= $item['quantity'] ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Categoría</label>
                              <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($item['category']) ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Precio</label>
                              <input type="number" step="0.01" name="price" class="form-control" value="<?= $item['price'] ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Proveedor</label>
                              <input type="text" name="supplier" class="form-control" value="<?= htmlspecialchars($item['supplier']) ?>" required>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-primary">Guardar cambios</button>
                      </div>
                    </form>
                  </div>
                </div>

                <!-- Modal Eliminar -->
                <div class="modal fade" id="deleteModal<?= $item['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title">Confirmar eliminación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                          ¿Estás seguro de que deseas eliminar el producto <strong><?= htmlspecialchars($item['name']) ?></strong>?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <a href="/inventory/delete/<?= $item['id'] ?>" class="btn btn-danger">Eliminar</a>
                      </div>
                    </div>
                  </div>
                </div>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No hay items en el inventario</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include_once LAYOUTS . 'main_foot.php'; ?>
