<?php

    namespace app\models;

    class user extends Model {

        public $table;
        protected $fillable = [
            'name',
            'username',
            'email',
            'passwd',
            'tipo',
            'activo',
        ];

        public $values = [];

        public function __construct(){
            parent::__construct();
            $this -> table = $this -> connect();
        }

    }