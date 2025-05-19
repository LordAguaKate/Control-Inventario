<?php
namespace app\models;

class inventory extends Model {
    public $table;
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

    public function __construct(){
        parent::__construct();
        $this->table = $this->connect();
    }

    public $values = [];

    public function getAllItems($limit = 10){
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
        ->orderBy([['created_at', 'desc']])
        ->limit($limit)
        ->get();
        
        return $result;
    }

    public function getItem($id){
        $result = $this->select([
            'a.id',
            'a.name',
            'a.description',
            'a.quantity',
            'a.category',
            'a.price',
            'a.supplier',
            'a.min_stock',
            'date_format(a.created_at,"%d/%m/%Y") as fecha'
        ])
        ->where([['a.id',$id]])
        ->get();
        return $result;
    }

    public function create(){
        if(empty($this->values)) {
            return false;
        }

        $data = [
            'name' => $this->values[0],
            'description' => $this->values[1],
            'quantity' => $this->values[2],
            'category' => $this->values[3],
            'price' => $this->values[4],
            'supplier' => $this->values[5],
            'min_stock' => $this->values[6],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->insert($data);
    }

    public function update($id){
        if(empty($this->values) || !is_numeric($id)) {
            return false;
        }

        $data = [
            'name' => $this->values[0],
            'description' => $this->values[1],
            'quantity' => $this->values[2],
            'category' => $this->values[3],
            'price' => $this->values[4],
            'supplier' => $this->values[5],
            'min_stock' => $this->values[6],
            'updated_at' => date('Y-m-d H:i:s')
        ];

        return $this->where([['id', $id]])->update($data);
    }

    public function delete($where = []) {
        // Si se pasa un ID numérico, lo convierte a condición por id
        if (is_numeric($where)) {
            $where = [['id', $where]];
        }
        return parent::delete($where);
    }

    public function insert() {
    $db = $this->table;
    
    // Usando $this->fillable para construir la consulta dinámicamente
    $columns = implode(', ', $this->fillable);
    $placeholders = implode(', ', array_fill(0, count($this->fillable), '?'));
    
    $sql = "INSERT INTO inventory ($columns) VALUES ($placeholders)";
    $stmt = $db->prepare($sql);
    return $stmt->execute($this->values);
    }
}