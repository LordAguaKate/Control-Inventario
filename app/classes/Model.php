<?php
namespace app\classes;

class Model {
    protected $table;
    protected $fillable = [];
    protected $error;
    protected $table_name;

    protected function getTableName() {
        return $this->table_name;
    }

    public function __construct() {
        $this->connect();
    }

    protected function connect() {
        try {
            $conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($conn->connect_error) {
                $this->error = "Error de conexión: " . $conn->connect_error;
                return null;
            }
            
            $conn->set_charset("utf8");
            return $conn;
        } catch (\Exception $e) {
            $this->error = "Error de conexión: " . $e->getMessage();
            return null;
        }
    }

    protected function insert($data) {
        if (empty($this->table)) {
            $this->error = "No hay conexión a la base de datos";
            return false;
        }

        $fields = implode(", ", array_keys($data));
        $values = implode(", ", array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO " . $this->getTableName() . " ($fields) VALUES ($values)";
        
        if (!$stmt = $this->table->prepare($sql)) {
            $this->error = "Error preparando la consulta: " . $this->table->error;
            return false;
        }

        $types = '';
        $params = [];
        
        foreach ($data as $value) {
            if (is_int($value)) {
                $types .= 'i';
            } elseif (is_float($value)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $params[] = $value;
        }

        array_unshift($params, $types);
        
        if (!$stmt->bind_param(...$params)) {
            $this->error = "Error en bind_param: " . $stmt->error;
            return false;
        }

        if (!$stmt->execute()) {
            $this->error = "Error ejecutando la consulta: " . $stmt->error;
            return false;
        }

        return true;
    }

    public function getError() {
        return $this->error;
    }
}
