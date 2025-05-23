<?php

namespace app\classes;

class DB {
    // Atributos de configuración de la base de datos
    public $db_host;
    public $db_name;
    private $db_user;
    private $db_passwd;

    public $conex; // Atributo de conexión
    public $table; // Necesario para las operaciones

    // Propiedades de el manejo de datos
    protected $fillable = [];
    public $values = [];

    // Atributos de control para las consultas
    public $s = " * ";
    public $c = "";
    public $j = "";
    public $w = " 1 ";
    public $o = "";
    public $l = "";

    public function __construct($dbh = DB_HOST, $dbn = DB_NAME, $dbu = DB_USER, $dbp = DB_PASS){
        $this->db_host   = $dbh;
        $this->db_name   = $dbn;
        $this->db_user   = $dbu;
        $this->db_passwd = $dbp;
    }

    public function connect(){
        $this->conex = new \mysqli($this->db_host, $this->db_user, $this->db_passwd, $this->db_name);
        if($this->conex->connect_errno){
            throw new \Exception("Error al conectarse a la BD: " . $this->conex->connect_error);
        }
        $this->conex->set_charset("utf8");
        $this->table = $this->conex; // Asignamos la conexión a table
        return $this->conex;
    }

    public function all(){
        return $this;
    }

    public function select($cc = []){
        if(count($cc) > 0){
            $this->s = implode(",", $cc);
        }
        return $this;
    }

    public function count($c = "*"){
        $this->c = ", count(" . $c . ") as tt ";
        return $this;
    }

    public function join($join = "", $on = ""){
        if($join != "" && $on != ""){
            $this->j .= ' join ' . $join . ' on ' . $on;
        }
        return $this;
    }

    public function where($ww = []){
        $this->w = "";
        if(count($ww) > 0){
            foreach($ww as $wheres){
                $this->w .= $wheres[0] . " = '" . $this->conex->real_escape_string($wheres[1]) . "' and ";
            }
        }
        $this->w .= ' 1 ';
        $this->w = ' (' . $this->w . ') ';
        return $this;
    }

    public function orderBy($ob = []){
        $this->o = "";
        if(count($ob) > 0){
            foreach($ob as $orderBy){
                $this->o .= $orderBy[0] . ' ' . $orderBy[1] . ',';
            }
            $this->o = ' order by ' . trim($this->o, ',');
        }
        return $this;
    }

    public function limit($l = ""){
        $this->l = "";
        if($l != ""){
            $this->l = ' limit ' . $l;
        }
        return $this;
    }

    public function get(){
        $sql = "select " .
                $this->s . 
                $this->c .
                " from " . strtolower(str_replace("app\\models\\", "", get_class($this))) .
                ($this->j != "" ? " a " . $this->j : "") .
                " where " . $this->w .
                $this->o . 
                $this->l;
        
        $r = $this->table->query($sql);
        if(!$r){
            throw new \Exception("Error en la consulta: " . $this->table->error);
        }
        
        $result = [];
        while($f = $r->fetch_assoc()){
            $result[] = $f;
        }
        return json_encode($result);
    }

    public function create(){
        $tableName = strtolower(str_replace("app\\models\\", "", get_class($this)));
        $placeholders = implode(",", array_fill(0, count($this->fillable), "?"));
        $types = str_repeat("s", count($this->fillable)); // Todos como strings
        
        $sql = "insert into " . $tableName .
               " (" . implode(",", $this->fillable) . ") values (" . $placeholders . ")";
        
        $stmt = $this->table->prepare($sql);
        if(!$stmt){
            throw new \Exception("Error al preparar la consulta: " . $this->table->error);
        }
        
        $stmt->bind_param($types, ...$this->values);
        if(!$stmt->execute()){
            throw new \Exception("Error al ejecutar la consulta: " . $stmt->error);
        }
        
        return $stmt->insert_id;
    }

    public function delete(){
        $tableName = strtolower(str_replace("app\\models\\", "", get_class($this)));
        $sql = "delete from " . $tableName . " where " . $this->w;
        
        $result = $this->table->query($sql);
        if(!$result){
            throw new \Exception("Error al eliminar: " . $this->table->error);
        }
        
        return $result;
    }
}