<?php
namespace app\controllers;

use app\models\UserPosts;
use app\classes\Views;

class MainController {
    private $userPosts;
    
    public function __construct() {
        $this->userPosts = new UserPosts();
    }
    
    public function index() {
        // Cargar el modelo de posts
        $postsModel = new \App\Models\UserPosts();
        $posts = $postsModel->getAllPosts();
        
        $data = [
            'title' => 'Inicio',
            'posts' => $posts
        ];

        Views::render('main', $data);
    }
}
