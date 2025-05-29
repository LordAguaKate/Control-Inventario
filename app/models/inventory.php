<?php
namespace app\models;

class inventory extends Model {
    // Mantenemos public para coincidir con la clase Model
    public $table;
    
    protected $fillable = [
        'name',
        'image_url',
        'description',
        'quantity', 
        'category',
        'price',
        'supplier',
        'min_stock',
        'created_at',
        'updated_at'
    ];

    protected $error; // Para manejo de errores

    public function __construct(){
        parent::__construct();
        $this->table = $this->connect();
    }

    public $values = [];

    // Método para obtener errores
    public function getError() {
        return $this->error;
    }

    public function getAllItems($limit = 10){
        try {
            $result = $this->select([
                'id',
                'name',
                'image_url',
                'description',
                'quantity', 
                'category',
                'price',
                'supplier',
                'min_stock',
                'DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'
            ])
            ->orderBy([['created_at', 'desc']])
            ->limit($limit)
            ->get();
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getItem($id){
        try {
            $result = $this->select([
                'id',
                'name',
                'image_url',
                'description',
                'quantity',
                'category',
                'price',
                'supplier',
                'min_stock',
                'DATE_FORMAT(created_at, "%d/%m/%Y") as fecha'
            ])
            ->where([['id', $id]])
            ->get();
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function create(){
        if(empty($this->values)) {
            $this->error = 'No hay datos para crear el item';
            return false;
        }

        try {
            $data = [
                'name' => $this->values[0] ?? '',
                'image_url' => $this->values[1] ?? '',
                'description' => $this->values[2] ?? '',
                'quantity' => (int)($this->values[3] ?? 0),
                'category' => $this->values[4] ?? '',
                'price' => $this->values[5] ?? '0.00',
                'supplier' => $this->values[6] ?? '',
                'min_stock' => (int)($this->values[7] ?? 0),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            // Validar que los campos requeridos no estén vacíos
            if (empty($data['name'])) {
                $this->error = 'El nombre es requerido';
                return false;
            }

            // Validar que los campos numéricos sean válidos
            if (!is_numeric($data['quantity']) || $data['quantity'] < 0) {
                $this->error = 'La cantidad debe ser un número positivo';
                return false;
            }

            if (!is_numeric($data['price'])) {
                $this->error = 'El precio debe ser un número válido';
                return false;
            }

            $result = $this->insert($data);
            
            if(!$result) {
                $this->error = $this->table->error ?? 'Error al insertar en la base de datos';
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function update($id){
        if(empty($this->values) || !is_numeric($id)) {
            $this->error = 'Datos o ID inválidos';
            return false;
        }

        try {
            $data = [
                'name' => $this->values[0],
                'description' => $this->values[1],
                'quantity' => (int)$this->values[2],
                'category' => $this->values[3],
                'price' => number_format((float)$this->values[4], 2, '.', ''),
                'supplier' => $this->values[5],
                'min_stock' => (int)$this->values[6],
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->where([['id', $id]])->update($data);
            
            if(!$result) {
                $this->error = $this->table->error;
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function delete($where = []) {
        try {
            if (is_numeric($where)) {
                $where = [['id', $where]];
            }
            
            $result = parent::delete($where);
            
            if(!$result) {
                $this->error = $this->table->error;
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function insert($data) {
        try {
            // Asegurarse de que los datos estén en el formato correcto
            $data = array_map(function($value) {
                if (is_numeric($value)) {
                    return $value;
                }
                return $value;
            }, $data);

            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            
            $sql = "INSERT INTO inventory ($columns) VALUES ($placeholders)";
            $stmt = $this->table->prepare($sql);
            
            if(!$stmt) {
                $this->error = $this->table->error ?? 'Error al preparar la consulta';
                return false;
            }
            
            // Tipos de parámetros (s = string, i = integer, d = double)
            $types = '';
            $values = [];
            foreach($data as $value) {
                if(is_int($value)) {
                    $types .= 'i';
                    $values[] = (int)$value;
                } elseif(is_float($value) || (is_string($value) && is_numeric($value))) {
                    $types .= 'd';
                    $values[] = (float)$value;
                } else {
                    $types .= 's';
                    $values[] = (string)$value;
                }
            }
            
            $stmt->bind_param($types, ...$values);
            $success = $stmt->execute();
            
            if(!$success) {
                $this->error = $stmt->error ?? 'Error al ejecutar la consulta';
                return false;
            }
            
            $insert_id = $stmt->insert_id;
            $stmt->close();
            
            return $insert_id;
        } catch (\Exception $e) {
            $this->error = 'Error en insert: ' . $e->getMessage();
            return false;
        }
    }
}