<?php 
    namespace app\models;

    class post extends Model{

        protected $table;
        protected $fillable = [
            'userId',
            'title',
            'body',
            'active',
            'image'
        ];

        public function __construct(){
            parent::__construct();
            //$this->table = $this->connect();
        }
        public $values = [];

        public function getAllPosts($limit = 10){
            $result = $this->select() -> get();
        }

    }