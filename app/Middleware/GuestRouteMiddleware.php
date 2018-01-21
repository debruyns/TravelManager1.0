<?php

namespace App\Middleware;

class GuestRouteMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($this->container->AuthHelper->checkSession()) {
            return $response->withRedirect($this->container->router->pathFor('dashboard'));
        }

        if ($this->container->AuthHelper->checkTwoFactor()) {
            return $response->withRedirect($this->container->router->pathFor('auth.verify'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
