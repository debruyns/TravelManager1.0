<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class DashboardController extends Controller {

    public function getDashboard($request, $response) {
        return $this->view->render($response, 'dashboard/dashboard.twig');
    }

}
