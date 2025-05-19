<?php 

    namespace app\controllers;
    use app\classes\Views as View;
    use app\controllers\auth\SessionController as SC;
    class HomeController extends Controller {

        public function index($params = null){
            $response = [
                'ua' => SC::sessionValidate() ?? (object)[
                    'sv' => false,
                    'id' => '',
                    'username' => '',
                    'tipo' => ''
                ],
                'code' => 200,
                'title' => 'Foro Fie 2025'
            ];
            
            View::render('home', $response);
        }

    }