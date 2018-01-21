<?php

namespace App\Helpers;

use App\Models\User;
use App\Middleware\AuthenticatorMiddleware;

class AuthHelper {

  public function getSessionUser() {
    if (isset($_SESSION['user_id'])) {
      return User::find($_SESSION['user_id']);
    } else {
      return null;
    }
  }

  public function checkSession() {
    return isset($_SESSION['user_id']);
  }

  public function checkTwoFactor() {
    return isset($_SESSION['user_twofactor']);
  }

  public function userVerify($request, $container) {

    // Initialize return variables
    $return_success = false;

    $user = User::find($_SESSION['user_twofactor']);
    if ($user){
      $authenticator = new AuthenticatorMiddleware();
      if ($authenticator->verifyCode($user->twofactor, $request->getParam('code'), 0)) {

        unset($_SESSION['user_twofactor']);
        $_SESSION['user_id'] = $user->id;
        $user->last_login = Date('Y-m-d H:i:s');
        $user->save();
        $return_success = true;

      } else {
        $return_success = false;
        $container->flash->addMessage('error_code', $container->translator->trans('auth.validation.verifyCode'));
      }

    } else {
      $return_success = false;
    }

    // Return validation object
    return (object) [
      'success' => $return_success
    ];

  }

  public function userSignIn($request, $container) {

    // Initialize validation output
    $return_success = false;
    $return_email = null;
    $return_password = null;
    $return_general = null;
    $return_twofactor = false;

    // Get the request parameters
    $i_email = trim($request->getParam('email'));
    $i_password = trim($request->getParam('password'));

    if (!empty($i_email) && !empty($i_password)) {

      $user = User::where('email', $i_email)->first();

      if ($user) {

        if (password_verify($i_password, $user->password)) {

          if ($user->active == 'true') {

            if ($user->status == 'normal') {

              unset($_SESSION['user_language']);
              unset($_SESSION['user_id']);
              unset($_SESSION['user_twofactor']);

              $_SESSION['user_language'] = $user->language;
              $return_success = true;

              if (!empty($user->twofactor)) {
                $_SESSION['user_twofactor'] = true;
                $return_twofactor = true;
              } else {
                $_SESSION['user_id'] = $user->id;
                $return_twofactor = false;
                $user->last_login = Date('Y-m-d H:i:s');
                $user->save();
              }

            } else {
              if ($user->status == 'archived') {
                $return_success = false;
                $return_general = $container->translator->trans('auth.validation.userArchived');
              }
              if ($user->status == 'suspended') {
                $return_success = false;
                $return_general = $container->translator->trans('auth.validation.userSuspended');
              }
            }

          } else {
            $return_success = false;
            $return_general = $container->translator->trans('auth.validation.notActive');
          }

        } else {
          $return_success = false;
          $return_password = $container->translator->trans('auth.validation.passwordIncorrect');
        }

      } else {
        $return_success = false;
        $return_email = $container->translator->trans('auth.validation.emailUnknown');
      }

    } else {
      $return_success = false;
      if (empty($i_email)) {
        $return_email = $container->translator->trans('auth.validation.required');
      }
      if (empty($i_password)) {
        $return_password = $container->translator->trans('auth.validation.required');
      }
    }

    if ($return_email) {
      $container->flash->addMessage('error_email', $return_email);
    }

    if ($return_password) {
      $container->flash->addMessage('error_password', $return_password);
    }

    if ($return_general) {
      $container->flash->addMessage('error', $return_general);
    }

    // Return validation object
    return (object) [
      'success' => $return_success,
      'twofactor' => $return_twofactor
    ];

  }

}
