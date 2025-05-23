<?php
namespace app\models;

use app\classes\DB as DB;

class Model extends DB {
    // Definimos la propiedad table para consistencia
    public $table;
    
    public function __construct(){
        parent::__construct();
    }
}