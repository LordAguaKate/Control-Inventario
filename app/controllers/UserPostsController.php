<?php
namespace app\controllers;

use app\models\Inventory;
use app\classes\Views;
use app\controllers\auth\SessionController as SC;

class UserPostsController {
    private $conn;

    public function __construct() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Crear conexión a la base de datos
        $this->conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    public function index() {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['sv']) || !$_SESSION['sv']) {
            return Views::render('auth/login', [
                'error' => 'Debes iniciar sesión para ver tus publicaciones'
            ]);
        }

        // Consulta SQL para obtener todas las publicaciones
        $sql = "SELECT 
                    id,
                    name,
                    description,
                    quantity,
                    category,
                    price,
                    supplier,
                    created_at,
                    DATE_FORMAT(created_at, '%d/%m/%Y') as fecha_formateada
                FROM inventory
                ORDER BY created_at DESC";

        $result = $this->conn->query($sql);

        if (!$result) {
            return Views::render('user/posts', [
                'error' => 'Error al obtener las publicaciones: ' . $this->conn->error,
                'posts' => [],
                'd' => (object)[
                    'title' => 'Mis Publicaciones',
                    'ua' => (object)[
                        'sv' => true,
                        'username' => $_SESSION['username']
                    ]
                ]
            ]);
        }

        // Obtener las publicaciones como array asociativo
        $posts = [];
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }

        // Cerrar el resultado y la conexión
        $result->close();
        $this->conn->close();

        // Crear el objeto de datos principal
        $data = (object)[
            'posts' => $posts,
            'title' => 'Mis Publicaciones',
            'ua' => (object)[
                'sv' => $_SESSION['sv'],
                'id' => $_SESSION['id'],
                'username' => $_SESSION['username'],
                'tipo' => $_SESSION['tipo']
            ],
            'code' => 200
        ];
        
        // Pasar los datos a la vista
        return Views::render('user/posts', (array)$data);
    }

    public function update_post($id) {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['sv']) || !$_SESSION['sv']) {
            return Views::render('auth/login', [
                'error' => 'Debes iniciar sesión para editar una publicación'
            ]);
        }

        // Validar datos
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        $category = $_POST['category'] ?? '';
        $price = $_POST['price'] ?? 0;
        $supplier = $_POST['supplier'] ?? '';

        if (empty($name) || empty($description) || empty($category) || empty($supplier)) {
            return Views::render('user/posts', [
                'error' => 'Todos los campos son requeridos',
                'd' => (object)[
                    'title' => 'Editar Publicación',
                    'ua' => (object)[
                        'sv' => true,
                        'username' => $_SESSION['username']
                    ]
                ]
            ]);
        }

        // Actualizar la publicación usando la conexión directa
        $sql = "UPDATE inventory 
                SET name = ?, 
                    description = ?, 
                    quantity = ?, 
                    category = ?, 
                    price = ?, 
                    supplier = ?
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdssdj", $name, $description, $quantity, $category, $price, $supplier, $id);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            header('Location: /posts');
            exit;
        } else {
            $error = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return Views::render('user/posts', [
                'error' => 'Error al actualizar la publicación: ' . $error,
                'd' => (object)[
                    'title' => 'Editar Publicación',
                    'ua' => (object)[
                        'sv' => true,
                        'username' => $_SESSION['username']
                    ]
                ]
            ]);
        }
    }

    public function delete_post($id) {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['sv']) || !$_SESSION['sv']) {
            return Views::render('auth/login', [
                'error' => 'Debes iniciar sesión para eliminar una publicación'
            ]);
        }

        // Eliminar la publicación usando la conexión directa
        $sql = "DELETE FROM inventory WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            header('Location: /posts');
            exit;
        } else {
            $error = $this->conn->error;
            $stmt->close();
            $this->conn->close();
            return Views::render('user/posts', [
                'error' => 'Error al eliminar la publicación: ' . $error,
                'd' => (object)[
                    'title' => 'Mis Publicaciones',
                    'ua' => (object)[
                        'sv' => true,
                        'username' => $_SESSION['username']
                    ]
                ]
            ]);
        }
    }
}
