<?php

namespace App\Controllers;

use Braintree\ClientToken;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;

class PaymentController extends Controller {

    public function getToken(Request $request, Response $response) {
      return $response->withJson([
        'token' => ClientToken::generate()
      ]);
    }

}
