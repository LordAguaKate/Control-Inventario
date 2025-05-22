<?php include LAYOUTS . 'main_head.php';

    setHeader((object) get_defined_vars());

?>

<div class="container product-form-container mt-4">
    <h2>Agregar Nuevo Producto</h2>
    
    <form action="/inventory/store" method="POST">
        <div class="form-group inventory">
            <label>Nombre:</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        
        <div class="form-group inventory">
            <label>Descripción:</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group inventory">
                    <label>Cantidad:</label>
                    <input type="number" name="quantity" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group inventory">
                    <label>Stock Mínimo:</label>
                    <input type="number" name="min_stock" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group inventory">
                    <label>Categoría:</label>
                    <input type="text" name="category" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group inventory">
                    <label>Precio:</label>
                    <input type="number" step="0.01" name="price" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="form-group inventory">
            <label>Proveedor:</label>
            <input type="text" name="supplier" class="form-control">
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="/inventory" class="btn btn-cancel">Cancelar</a>

    </form>
</div>

<?php include_once '../resources/layouts/main_foot.php'; ?>