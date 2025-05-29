<?php
include_once LAYOUTS . 'main_head.php';
setHeader($d);
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Mis Publicaciones</h2>
        </div>
    </div>

    <?php if (!empty($d->posts)): ?>
        <div class="row">
            <?php foreach ($d->posts as $post): ?>
                <?php 
                // Asegurarse de que $post sea un array
                $post = (array)$post;
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= htmlspecialchars($post['name'] ?? 'Sin nombre') ?></h5>
                            <div class="mb-2">
                                <span class="badge bg-secondary"><?= htmlspecialchars($post['category'] ?? 'Sin categoría') ?></span>
                                <span class="badge bg-success ms-1">$<?= number_format($post['price'] ?? 0, 2) ?></span>
                            </div>
                            <p class="card-text"><?= nl2br(htmlspecialchars($post['description'] ?? 'Sin descripción')) ?></p>
                            <div class="mt-auto">
                                <p class="mb-1">
                                    <small class="text-muted">
                                        <strong>Disponibles:</strong> <?= htmlspecialchars($post['quantity'] ?? 0) ?>
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        <strong>Proveedor:</strong> <?= htmlspecialchars($post['supplier'] ?? 'No especificado') ?>
                                    </small>
                                </p>
                                <p class="mb-0">
                                    <small class="text-muted">
                                        <strong>Agregado:</strong> <?= htmlspecialchars($post['fecha_formateada'] ?? 'N/A') ?>
                                    </small>
                                </p>
                                
                                <!-- Botones de Acción -->
                                <div class="mt-3">
                                    <!-- Botón Editar -->
                                    <button 
                                        class="btn btn-sm btn-warning me-2" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal<?= $post['id'] ?>"
                                    >
                                        Editar
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <button 
                                        class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal<?= $post['id'] ?>"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Editar -->
                <div class="modal fade" id="editModal<?= $post['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <form action="/posts/update_post/<?= $post['id'] ?>" method="POST" class="modal-content">
                        <input type="hidden" name="_method" value="PUT">
                      <div class="modal-header">
                        <h5 class="modal-title">Editar Publicación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                          <div class="mb-3">
                              <label>Nombre</label>
                              <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($post['name']) ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Cantidad</label>
                              <input type="number" name="quantity" class="form-control" value="<?= $post['quantity'] ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Categoría</label>
                              <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($post['category']) ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Precio</label>
                              <input type="number" step="0.01" name="price" class="form-control" value="<?= $post['price'] ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Proveedor</label>
                              <input type="text" name="supplier" class="form-control" value="<?= htmlspecialchars($post['supplier']) ?>" required>
                          </div>
                          <div class="mb-3">
                              <label>Descripción</label>
                              <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($post['description']) ?></textarea>
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
                <div class="modal fade" id="deleteModal<?= $post['id'] ?>" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog">
                    <form action="/posts/delete_post/<?= $post['id'] ?>" method="POST" class="modal-content">
                        <input type="hidden" name="_method" value="DELETE">
                      <div class="modal-header">
                          <h5 class="modal-title">Confirmar eliminación</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                      </div>
                      <div class="modal-body">
                          ¿Estás seguro de que deseas eliminar la publicación <strong><?= htmlspecialchars($post['name']) ?></strong>?
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-danger">Eliminar</button>
                      </div>
                    </form>
                  </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            No tienes publicaciones en este momento.
        </div>
    <?php endif; ?>
</div>

<?php
include_once LAYOUTS . 'main_foot.php';
setFooter($d);
closefooter();
?>
