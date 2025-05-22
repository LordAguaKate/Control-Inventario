<?php
function setHeader($args){
    $ua = isset($args->ua) ? as_object($args->ua) : (object)[];
?>
<!DOCTYPE html>
<html lang="es">
<head>    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link rel="stylesheet" href="/assets/css/inventory-index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <title><?= isset($args->title) ? $args->title : '' ?></title>
    <style>
        html, body {
            height: 100%;
            background-color: #CCC;
            color: #888;
        }
        .sidebar {
            min-height: 100vh;
        }
    </style> 
</head>
<body class="<?= $bodyClass ?? '' ?>">
<div id="app" class="container-fluid">
    <div class="row min-vh-100">
        <!-- Sidebar -->
        <div class="col-md-2 d-flex flex-column bg-dark text-white p-3 sidebar">
            <h2 class="mb-4">EasyStock</h2>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/" class="nav-link text-white">Inicio</a>
                </li>                        
                <?php if(isset($ua->sv) && $ua->sv ) : ?>
                    <li class="nav-item">
                        <a href="/UserPosts" class="nav-link text-white">Mis publicaciones</a>
                    </li>
                    <li class="nav-item">
                        <a href="/inventory" class="nav-link text-white">Inventario</a>
                    </li>
                <?php endif ?>
            </ul>
            
            <?php if(isset($ua->sv) && $ua->sv): ?>
            <!-- Botón cerrar sesión abajo -->
            <div class="mt-auto pt-3 border-top">
                <div class="dropdown w-100">
                    <a class="btn btn-secondary dropdown-toggle w-100" href="#" role="button" data-bs-toggle="dropdown">
                        <?=isset($ua->username) ? $ua->username : 'Bienvenido' ?>
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li>
                            <a href="/Session/logout" class="dropdown-item logout-btn">Cerrar sesión</a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php else: ?>
            <div class="mt-auto">
                <a href="/Session/iniSession" class="btn btn-success login-btn">Iniciar sesión</a>
            </div>
            <?php endif ?>
        </div>

        <!-- Contenido principal -->
        <main class="col-md-10 p-4">
            <!-- Buscador -->
            <div class="d-flex justify-content-end mb-4">
                <form class="d-flex w-50" role="search">
                    <input class="form-control me-2" id="buscar-palabra" type="search" placeholder="Buscar" aria-label="Search">
                    <button class="btn btn-outline-success" type="button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
<?php
}
