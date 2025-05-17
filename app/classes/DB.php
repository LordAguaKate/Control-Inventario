<?php

    namespace app\classes;

    class DB {
        //Atributos de configuración de la base de datos
        public $db_host;
        public $db_name;
        private $db_user;
        private $db_passwd;

        public $conex; //Atributo de conexión

        //Atributos de control para las consultas
        public $s = " * ";
        public $c = "";
        public $j = "";
        public $w = " 1 ";
        public $o = "";
        public $l = "";

        public function __construct($dbh = DB_HOST,$dbn = DB_NAME,$dbu = DB_USER, $dbp = DB_PASS){
            $this->db_host   = $dbh;
            $this->db_name   = $dbn;
            $this->db_user   = $dbu;
            $this->db_passwd = $dbp;
        }

        public function connect(){
            $this->conex = new \mysqli($this->db_host,$this->db_user,$this->db_passwd,$this->db_name);
            if( $this->conex->connect_errno ){
                echo "Error al conectarse a la BD " . $this->conex->connect_error;
                return;
            }
            $this->conex->set_charset("utf8");
            return $this->conex;
        }

        public function all(){
            return $this;
        }

        public function select($cc = []){
            if( count($cc) > 0){
                $this->s = implode(",",$cc);
            }
            return $this;
        }

        public function count( $c = "*" ){
            $this -> c = ", count(" . $c . ") as tt ";
            return $this;
        }

        public function join( $join = "", $on = "" ){
            if( $join != "" && $on != "" ){
                $this->j .= ' join ' . $join . ' on ' . $on;
            }
            return $this;
        }

        public function where( $ww = [] ){
            $this->w = "";
            if( count($ww) > 0 ){
                foreach( $ww as $wheres ){
                    $this->w .= $wheres[0] . " like '" . $wheres[1] . "' and ";
                }
            }
            $this->w .= ' 1 ';
            $this->w = ' (' . $this->w . ') ';
            return $this;
        }

        public function orderBy( $ob = []){
            $this->o = "";
            if( count($ob) > 0 ){
                foreach( $ob as $orderBy ){
                    $this->o .= $orderBy[0] . ' ' . $orderBy[1] . ',';
                }
                $this->o = ' order by ' . trim($this->o,',');
            }
            return $this;
        }

        public function limit($l = ""){
            $this->l = "";
            if( $l != "" ){
                $this->l = ' limit ' . $l;
            }
            return $this;
        }

        public function get(){
            $sql = "select " .
                        $this->s . 
                        $this->c .
                        " from " .  str_replace("app\\models\\","",get_class( $this )) .
                        ( $this->j != "" ? " a " . $this->j : "") .
                        " where " . $this->w .
                        $this->o . 
                        $this->l;
            //echo $sql;die; 
            $r = $this->table->query( $sql );
            $result = [];
            while( $f = $r->fetch_assoc() ){
                $result[] = $f;
            }
            return json_encode( $result );

        }

        public function create(){
            $sql = "insert into" . str_replace("app\\models\\","",get_class( $this )) .
                    '(' . implode(",",$this->fillable).') values (' .
                    trim(str_replace("&","?,",str_pad("",count($this->fillable),"&")),","). ');';
            $stmt = $this->table->prepare($sql);
            $stmt->bind_param( str_pad("",count($this->fillable),"2"), ...$this->values);
            $stmt->execute();
            return $stmt->insert_id;
        }

        public function delete(){
            $sql = 'delete from' . str_replace("app\\models\\","",get_class( $this )) .
                    'where' . $this->w; 
            return $this->table->query(sql);
        }

    }