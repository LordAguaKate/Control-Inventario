<?php include_once '../resources/layouts/main_head.php'; ?>

<div class="container mt-4">
    <h2>Editar Item</h2>
    
    <form action="/inventory/update/<?= $item->id ?>" method="POST">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="name" class="form-control" value="<?= $item->name ?>" required>
        </div>
        
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="description" class="form-control"><?= $item->description ?></textarea>
        </div>
        
        <div class="form-group">
            <label>Cantidad:</label>
            <input type="number" name="quantity" class="form-control" value="<?= $item->quantity ?>" required>
        </div>
        
        <div class="form-group">
            <label>Categoría:</label>
            <input type="text" name="category" class="form-control" value="<?= $item->category ?>">
        </div>
        
        <div class="form-group">
            <label>Precio:</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= $item->price ?>">
        </div>
        
        <div class="form-group">
            <label>Proveedor:</label>
            <input type="text" name="supplier" class="form-control" value="<?= $item->supplier ?>">
        </div>
        
        <div class="form-group">
            <label>Stock Mínimo:</label>
            <input type="number" name="min_stock" class="form-control" value="<?= $item->min_stock ?>">
        </div>
        
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>
</div>

<?php include_once '../resources/layouts/main_foot.php'; ?>