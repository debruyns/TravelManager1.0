<?php

namespace App\Middleware;

class PersistInputMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (isset($_SESSION['persistInput'])) {
          $this->container->view->getEnvironment()->addGlobal('persistInput', $_SESSION['persistInput']);
        }
        
        $_SESSION['persistInput'] = $request->getParams();

        $response = $next($request, $response);
        return $response;
    }
}
