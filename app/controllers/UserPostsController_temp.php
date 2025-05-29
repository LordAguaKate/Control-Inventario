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

        // Consulta SQL para obtener las publicaciones del usuario actual
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
                WHERE user_id = ?
                ORDER BY created_at DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();

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
        $stmt->close();
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

    public function delete($id) {
        // Verificar si el usuario está autenticado
        if (!isset($_SESSION['sv']) || !$_SESSION['sv']) {
            return Views::render('auth/login', [
                'error' => 'Debes iniciar sesión para eliminar una publicación'
            ]);
        }

        // Eliminar la publicación
        $sql = "DELETE FROM inventory WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['id']);

        if ($stmt->execute()) {
            header('Location: /posts');
            exit;
        } else {
            return Views::render('user/posts', [
                'error' => 'Error al eliminar la publicación: ' . $this->conn->error,
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
