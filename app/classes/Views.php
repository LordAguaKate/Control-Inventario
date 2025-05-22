<?php

    namespace app\classes;

    class Views {

        public static function render($view, $data = []){
            // Convierte $data en array si es objeto
            if (is_object($data)) $data = (array)$data;
            // Extrae las variables del array $data
            extract($data);
            // También puedes extraer directamente $data si quieres variables sueltas:
            // extract($data);

            require_once VIEWS . $view . '.view.php';
            exit();
        }

    }