<?php 

namespace app\classes;

use app\controllers\ErrorController as ErrorController;

class Router{
    private $uri = "";
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => []
    ];
    
    public function __construct(){}

    // Métodos para definir rutas
    public function get($uri, $controllerAction) {
        $this->routes['GET'][$this->normalizeUri($uri)] = $controllerAction;
    }
    
    public function post($uri, $controllerAction) {
        $this->routes['POST'][$this->normalizeUri($uri)] = $controllerAction;
    }

    public function put($uri, $controllerAction) {
        $this->routes['PUT'][$this->normalizeUri($uri)] = $controllerAction;
    }

    public function delete($uri, $controllerAction) {
        $this->routes['DELETE'][$this->normalizeUri($uri)] = $controllerAction;
    }

    private function normalizeUri($uri) {
        return trim($uri, '/');
    }

    public function route(){
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = $this->normalizeUri($uri);

        // Buscar en rutas definidas primero
        foreach ($this->routes[$requestMethod] as $route => $controllerAction) {
            // Convertir parámetros como (:num) a expresiones regulares
            $pattern = preg_replace('/\(:num\)/', '(\d+)', $route);
            $pattern = str_replace('/', '\/', $pattern);
            
            if (preg_match('/^' . $pattern . '$/', $uri, $matches)) {
                list($controller, $method) = explode('@', $controllerAction);
                $controller = 'app\\controllers\\' . $controller;
                
                if (class_exists($controller)) {
                    $controller = new $controller();
                    $params = array_slice($matches, 1); // Obtener parámetros capturados
                    
                    if (method_exists($controller, $method)) {
                        $controller->$method($params);
                        return;
                    }
                }
            }
        }
        
        // Si no encuentra ruta definida, usar el sistema dinámico actual
        $this->filterRequest();
        $controller = $this->getController();
        $method     = $this->getMethod();
        $params     = $this->getParams();
        
        if (class_exists($controller)) {
            $controller = new $controller();
        } else {
            $controller = new ErrorController();
            $controller->error404();
            return;
        }
        
        if (!method_exists($controller, $method)) {
            $controller = new ErrorController();
            $controller->errorMNF();
            return;
        }
        
        $controller->$method($params); 
    }

    private function filterRequest(){
       $request = filter_input_array(INPUT_GET);
       if( isset($request['uri']) ){
        $this->uri = $request['uri'];
        $this->uri = rtrim($this->uri,'/');
        $this->uri = filter_var($this->uri,FILTER_SANITIZE_URL);
        $this->uri = explode('/',ucfirst(strtolower( $this->uri )));
        return;
       }
    }

    private function getController(){
        $controller = 'Home';
        if( isset( $this->uri[0]) ){
            $controller = $this->uri[0];
            unset($this->uri[0]);
        }
        $controller = ucfirst( $controller );
        if( $controller == 'Session' ) $controller = "auth\\Session";
        $controller = 'app\controllers\\' . $controller . 'Controller';
        return $controller;
    }

    private function getMethod(){
        $method = 'index';
        if( isset($this->uri[1]) ){
            $method = $this->uri[1];
            unset($this->uri[1]);
        }
        return $method;
    }

    private function getParams(){
        $params = [];
        if( !empty($this->uri) ){
            $params = $this->uri;
            $this->uri = "";
        }
        return $params;
    }
}