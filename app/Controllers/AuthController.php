<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class AuthController extends Controller {

  // HTTP GET Sign In
  public function getSignIn($request, $response) {

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('auth.signin.pageTitle')
      ]
    ];

    return $this->view->render($response, 'auth/login.twig', $viewData);

  }

  // HTTP POST Sign In
  public function postSignIn($request, $response) {
    $signIn = $this->AuthHelper->userSignIn($request, $this);
    if ($signIn->success === true){
      if ($signIn->twofactor === true) {
        return $response->withRedirect($this->router->pathFor('auth.verify'));
      } else {
        return $response->withRedirect($this->router->pathFor('dashboard'));
      }
    } else {
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }
  }

  // HTTP GET Sign Up
  public function getSignUp($request, $response) {
    return $this->view->render($response, 'auth/signup.twig');
  }

  // HTTP GET Verify
  public function getVerify($request, $response) {

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('auth.verify.pageTitle')
      ]
    ];

    return $this->view->render($response, 'auth/verify.twig', $viewData);

  }

  // HTTP POST Verify
  public function postVerify($request, $response) {
    $verify = $this->AuthHelper->userVerify($request, $this);
    if ($verify->success === true){
      return $response->withRedirect($this->router->pathFor('dashboard'));
    } else {
      return $response->withRedirect($this->router->pathFor('auth.verify'));
    }
  }

  // HTTP GET Cancel
  public function getCancel($request, $response) {

    unset($_SESSION['user_twofactor']);
    $this->flash->addMessage('warning', $this->translator->trans('auth.signin.cancelMsg'));
    return $response->withRedirect($this->router->pathFor('auth.signin'));

  }

  // HTTP GET logout
  public function getLogout($request, $response) {
    unset($_SESSION['user_id']);
    $this->flash->addMessage('success', $this->translator->trans('auth.signin.logoutMsg'));
    return $response->withRedirect($this->router->pathFor('auth.signin'));
  }

}
