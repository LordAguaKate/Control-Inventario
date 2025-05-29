<?php 

    namespace app\controllers;
    use app\classes\Views as View;
    use app\controllers\auth\SessionController as SC;

    class HomeController extends Controller {
        private $conn;

        public function __construct() {
            // Crear conexión a la base de datos
            $this->conn = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            if ($this->conn->connect_error) {
                die("Error de conexión: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8");
        }

        public function index($params = null) {
            // Obtener productos destacados
            $products = $this->getFeaturedProducts();
            
            // Obtener datos de sesión o usar valores por defecto
            $ua = SC::sessionValidate() ?? (object)[
                'sv' => false,
                'id' => '',
                'username' => 'Invitado',
                'tipo' => ''
            ];
            
            $response = [
                'ua' => $ua,
                'code' => 200,
                'title' => 'Inicio - Foro Fie 2025',
                'products' => $products
            ];
            
            // Cerrar la conexión
            $this->conn->close();
            
            View::render('home', $response);
        }
        
        private function getFeaturedProducts($limit = 8) {
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
                    ORDER BY created_at DESC
                    LIMIT ?";
                    
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            
            $stmt->close();
            return $products;
        }
    }