<?php
namespace app\models;

class UserPosts extends Model {
    protected $table_name = 'inventory';
    protected $error;

    public function __construct() {
        parent::__construct();
        $this->table = $this->connect();
    }

    public function getAllPosts($limit = 10) {
        if (!$this->table) {
            $this->error = "No hay conexión a la base de datos";
            return [];
        }

        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        if ($limit > 0) {
            $query .= " LIMIT " . (int)$limit;
        }
        
        $result = $this->table->query($query);
        
        if (!$result) {
            $this->error = "Error en la consulta: " . $this->table->error;
            return [];
        }
        
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        
        // Validar si hay productos
        if (empty($products)) {
            $this->error = "No se encontraron productos en el inventario";
            return [];
        }
        
        // Convertir productos a formato de posts
        $posts = [];
        foreach ($products as $product) {
            // Validar que el producto tenga los campos necesarios
            if (empty($product['name']) || !isset($product['description'], 
                $product['category'], $product['price'], 
                $product['supplier'])) {
                continue;
            }
            
            $posts[] = [
                'id' => $product['id'] ?? null,
                'name' => $product['name'],
                'description' => $product['description'],
                'created_at' => $product['created_at'] ?? date('Y-m-d H:i:s'),
                'category' => $product['category'],
                'price' => $product['price'],
                'supplier' => $product['supplier']
            ];
        }
        
        return $posts;
    }

    public function getError() {
        return $this->error ?? null;
    }
}