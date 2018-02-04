<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class HomeController extends Controller {

    public function getIndex($request, $response) {
        //return $this->view->render($response, 'dashboard.twig');
        return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

}
