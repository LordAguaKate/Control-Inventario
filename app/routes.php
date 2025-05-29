<?php
require_once __DIR__ . '/config.php';

// Autoload de clases
spl_autoload_register(function ($class) {
    $class = str_replace('\\', DS, $class);
    $file = CLASSES . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Rutas
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('?', $uri)[0];

// Ruta principal
if ($uri === '/') {
    $controller = new \app\controllers\MainController();
    $controller->index();
    exit;
}

// Rutas para posts
if (preg_match('#^/posts(/.*)?$#', $uri, $matches)) {
    $controller = new \app\controllers\UserPostsController();
    
    // Obtener el método y los parámetros de la URL
    $path = $matches[1] ?? '';
    $segments = array_filter(explode('/', $path));
    $method = array_shift($segments) ?: 'index';
    
    // Mapear el método del controlador
    $controllerMethod = $method;
    
    // Verificar si el método existe en el controlador
    if (method_exists($controller, $controllerMethod)) {
        // Llamar al método del controlador con los parámetros
        call_user_func_array([$controller, $controllerMethod], $segments ? [$segments[0]] : []);
        exit;
    }
}

// Rutas para el inventario
if (preg_match('#^/inventory(/.*)?$#', $uri, $matches)) {
    $controller = new \app\controllers\InventoryController();
    
    // Obtener el método y los parámetros de la URL
    $path = $matches[1] ?? '';
    $segments = array_filter(explode('/', $path));
    $method = array_shift($segments) ?: 'index';
    
    // Mapear métodos HTTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method'])) {
        $method = strtolower($_POST['_method']);
    }
    
    // Determinar el método del controlador a llamar
    $controllerMethod = $method;
    
    // Verificar si el método existe en el controlador
    if (method_exists($controller, $controllerMethod)) {
        // Llamar al método del controlador con los parámetros
        call_user_func_array([$controller, $controllerMethod], [$segments]);
        exit;
    }
}

// Ruta por defecto
require_once VIEWS . '404.view.php';
