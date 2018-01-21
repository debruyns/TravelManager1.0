<?php

namespace App\Middleware;

class TwoFactorRouteMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if (!$this->container->AuthHelper->checkTwoFactor()) {
            return $response->withRedirect($this->container->router->pathFor('auth.signin'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
