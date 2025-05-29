<?php include LAYOUTS . 'main_head.php';
setHeader($d);
?>

<div class="container mt-5">
    <div class="row">
        <!-- Inicio -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="card-title mb-4">Bienvenido a tu panel de control</h2>
                    
                    <?php if (isset($d->error)): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($d->error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($d->posts)): ?>
                        <div class="row">
                            <?php foreach ($d->posts as $post): ?>
                                <div class="col-md-4 mb-4">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($post->title); ?></h5>
                                            <p class="card-text"><?php echo nl2br(htmlspecialchars($post->content)); ?></p>
                                            <p class="text-muted"><small>Publicado: <?php echo $post->created_at; ?></small></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No hay publicaciones recientes.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <!-- Acceso rápido -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Mis Publicaciones</h5>
                    <p class="card-text">Administra tus publicaciones existentes o crea nuevas.</p>
                    <a href="/userposts" class="btn btn-success">Administrar Publicaciones</a>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Inventario</h5>
                    <p class="card-text">Gestiona tu inventario de productos.</p>
                    <a href="/inventory" class="btn btn-primary">Ver Inventario</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: transform 0.2s;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.card-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 1rem;
}

.card-text {
    color: #666;
    margin-bottom: 1.5rem;
}

.btn {
    padding: 0.5rem 1.5rem;
    font-weight: 500;
    border-radius: 4px;
}

.text-muted {
    font-size: 0.85rem;
}
</style>

<?php include LAYOUTS . 'main_foot.php'; ?>
