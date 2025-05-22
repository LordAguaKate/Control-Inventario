<?php
namespace app\models;

class inventory extends Model {
    protected $table = 'inventory';
    
    protected $fillable = [
        'name',
        'description',
        'quantity', 
        'category',
        'price',
        'supplier',
        'min_stock',
        'created_at',
        'updated_at'
    ];

    protected $error;
    public $values = [];

    public function __construct(){
        parent::__construct();
        $this->table = 'inventory';
        if(!$this->conex || !$this->conex->ping()) {
            $this->connect();
        }
    }

    // Método para obtener errores
    public function getError() {
        return $this->error ?? ($this->conex->error ?? 'Error desconocido');
    }

    public function getAllItems($limit = 0){
        try {
            $this->select([
                'id',
                'name',
                'description',
                'quantity', 
                'category',
                'price',
                'supplier',
                'min_stock',
                'DATE_FORMAT(created_at, "%d/%m/%Y %H:%i") as fecha_creacion',
                'DATE_FORMAT(updated_at, "%d/%m/%Y %H:%i") as fecha_actualizacion'
            ])
            ->orderBy([['updated_at', 'DESC']]);

            if($limit > 0) {
                $this->limit($limit);
            }

            $result = $this->get();
            
            return is_array($result) ? $result : [];
            
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            error_log('Error en inventory.getAllItems: ' . $e->getMessage());
            return [];
        }
    }

    public function getItem($id){
        try {
            $result = $this->select([
                'id',
                'name',
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
            
            return !empty($result) ? $result[0] : null;

        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            error_log('Error en inventory.getItem: ' . $e->getMessage());
            return null;
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
                'description' => $this->values[1] ?? '',
                'quantity' => (int)($this->values[2] ?? 0),
                'category' => $this->values[3] ?? '',
                'price' => number_format((float)($this->values[4] ?? 0), 2, '.', ''),
                'supplier' => $this->values[5] ?? '',
                'min_stock' => (int)($this->values[6] ?? 0),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $result = $this->insert($data);
            
            if(!$result) {
                $this->error = $this->conex->error;
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
                $this->error = $this->conex->error;
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
                $this->error = $this->conex->error;
            }
            
            return $result;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function insert($data) {
        try {
            $columns = implode(', ', array_keys($data));
            $placeholders = implode(', ', array_fill(0, count($data), '?'));
            
            $sql = "INSERT INTO inventory ($columns) VALUES ($placeholders)";
            $stmt = $this->conex->prepare($sql);
            
            if(!$stmt) {
                $this->error = $this->conex->error;
                return false;
            }
            
            // Tipos de parámetros (s = string, i = integer, d = double)
            $types = '';
            foreach($data as $value) {
                if(is_int($value)) $types .= 'i';
                elseif(is_float($value)) $types .= 'd';
                else $types .= 's';
            }
            
            $stmt->bind_param($types, ...array_values($data));
            $success = $stmt->execute();
            
            if(!$success) {
                $this->error = $stmt->error;
            }
            
            return $success ? $stmt->insert_id : false;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
}