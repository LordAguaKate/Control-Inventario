<?php
namespace app\controllers;

use app\models\inventory as InventoryModel;
use app\classes\Redirect;
use app\classes\Views;
use app\controllers\auth\SessionController as SC;

class InventoryController extends Controller {
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $inventory = new InventoryModel();
        $items = json_decode($inventory->getAllItems(), true);
        
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Gestión de Inventario',
            'items' => $items
        ];
        
        Views::render('inventory/index', $response);
    }

    public function create(){
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Agregar Item al Inventario'
        ];
        
        Views::render('inventory/create', $response);
    }

    public function store(){
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Validación mejorada
        if(empty($data['name'])) {
            $_SESSION['error'] = 'El nombre es requerido';
            Redirect::to('/inventory/create');
            return;
        }

        if(!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] < 0) {
            $_SESSION['error'] = 'La cantidad debe ser un número positivo';
            Redirect::to('/inventory/create');
            return;
        }

        if(isset($data['price']) && !is_numeric($data['price'])) {
            $_SESSION['error'] = 'El precio debe ser un número válido';
            Redirect::to('/inventory/create');
            return;
        }
        
        $inventory = new InventoryModel();
        $inventory->values = [
            $data['name'],
            $data['description'] ?? '',
            (int)$data['quantity'],
            $data['category'] ?? '',
            number_format((float)($data['price'] ?? 0), 2, '.', ''),
            $data['supplier'] ?? '',
            (int)($data['min_stock'] ?? 0),
            date('Y-m-d H:i:s'), // created_at
            date('Y-m-d H:i:s')  // updated_at
        ];
        
        if($inventory->create()){
            $_SESSION['success'] = 'Item creado exitosamente';
            Redirect::to('/inventory');
        } else {
            $_SESSION['error'] = 'Error al crear el item';
            if(method_exists($inventory, 'getError')) {
                $_SESSION['error'] .= ': ' . $inventory->getError();
            }
            Redirect::to('/inventory/create');
        }
    }

    public function edit($params = null){
        $id = $params[0] ?? null;
        
        if(!$id || !is_numeric($id)) {
            Redirect::to('/inventory');
            return;
        }
        
        $inventory = new InventoryModel();
        $item = json_decode($inventory->getItem($id));
        
        if(!$item) {
            Redirect::to('/inventory');
            return;
        }
        
        $response = [
            'ua' => SC::sessionValidate() ?? ['sv' => 0],
            'code' => 200,
            'title' => 'Editar Item',
            'item' => $item[0] ?? null
        ];
        
        Views::render('inventory/edit', $response);
    }

    public function update($params = null){
        $id = $params[0] ?? null;
        
        if(!$id || !is_numeric($id)) {
            Redirect::to('/inventory');
            return;
        }
        
        $data = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Validación mejorada
        if(empty($data['name'])) {
            $_SESSION['error'] = 'El nombre es requerido';
            Redirect::to('/inventory/edit/'.$id);
            return;
        }

        if(!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] < 0) {
            $_SESSION['error'] = 'La cantidad debe ser un número positivo';
            Redirect::to('/inventory/edit/'.$id);
            return;
        }

        if(isset($data['price']) && !is_numeric($data['price'])) {
            $_SESSION['error'] = 'El precio debe ser un número válido';
            Redirect::to('/inventory/edit/'.$id);
            return;
        }
        
        $inventory = new InventoryModel();
        $inventory->values = [
            $data['name'],
            $data['description'] ?? '',
            (int)$data['quantity'],
            $data['category'] ?? '',
            number_format((float)($data['price'] ?? 0), 2, '.', ''),
            $data['supplier'] ?? '',
            (int)($data['min_stock'] ?? 0),
            date('Y-m-d H:i:s')  // updated_at
        ];
        
        if($inventory->update($id)){
            $_SESSION['success'] = 'Item actualizado exitosamente';
            Redirect::to('/inventory');
        } else {
            $_SESSION['error'] = 'Error al actualizar el item';
            if(method_exists($inventory, 'getError')) {
                $_SESSION['error'] .= ': ' . $inventory->getError();
            }
            Redirect::to('/inventory/edit/'.$id);
        }
    }

    public function delete($params = null){
        $id = $params[0] ?? null;
        
        if(!$id || !is_numeric($id)) {
            Redirect::to('/inventory');
            return;
        }
        
        $inventory = new InventoryModel();
        
        if($inventory->delete($id)){
            $_SESSION['success'] = 'Item eliminado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al eliminar el item';
            if(method_exists($inventory, 'getError')) {
                $_SESSION['error'] .= ': ' . $inventory->getError();
            }
        }
        
        Redirect::to('/inventory');
    }
}