<?php

namespace App\Middleware;

class AuthRouteMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->AuthHelper->checkSession()) {
            if ($this->container->AuthHelper->checkTwoFactor()) {
              return $response->withRedirect($this->container->router->pathFor('auth.verify'));
            } else {
              return $response->withRedirect($this->container->router->pathFor('auth.signin'));
            }
        }

        $response = $next($request, $response);
        return $response;
    }
}
