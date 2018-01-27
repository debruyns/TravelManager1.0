<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Language;
use App\Middleware\AuthenticatorMiddleware;
use DateTime;

class AccountHelper {

  public function getLanguages($container) {
    $return = "";
    $languages = Language::where('active', 'true')->orderBy('name', 'ASC')->get();
    foreach ($languages as $language) {
      if ($language->code == $container->AuthHelper->getSessionUser()->language) {
        $return .= "<option selected value='".$language->code."'>".$language->name."</option>";
      } else {
        $return .= "<option value='".$language->code."'>".$language->name."</option>";
      }
    }
    return $return;
  }

  public function updateLanguage($request, $container) {

    $return_success = false;
    $return_language = null;

    $language = $request->getParam('language');
    $dbLanguage = Language::where('code', $language)->first();

    if ($dbLanguage) {

      if ($dbLanguage->active == 'true') {

        $sessionUser = $container->AuthHelper->getSessionUser();
        if ($sessionUser->language != $dbLanguage->code){
          $sessionUser->language = $dbLanguage->code;
          $sessionUser->save();
          $_SESSION['user_language'] = $dbLanguage->code;
        }

        $return_success = true;

      } else {
        $return_language = $container->translator->trans('auth.validation.error');
        $return_success = false;
      }

    } else {
      $return_language = $container->translator->trans('auth.validation.error');
      $return_success = false;
    }

    if ($return_language) {
      $container->flash->addMessage('error_language', $return_language);
    }

    return $return_success;

  }

  public function updatePassword($request, $container) {

    $return_current = null;
    $return_new = null;
    $return_confirm = null;
    $return_success = false;
    $updated = false;

    $current = trim($request->getParam('currentPassword'));
    $new = trim($request->getParam('newPassword'));
    $confirm = trim($request->getParam('confirmPassword'));
    $sessionUser = $container->AuthHelper->getSessionUser();

    if (!empty($current) && !empty($new) && !empty($confirm)) {

      if (password_verify($current, $sessionUser->password)) {

        if (strlen($new) >= 8) {

          if ($new == $confirm) {

            if (!password_verify($new, $sessionUser->password)) {
              $updated = true;
              $hash = password_hash($new, PASSWORD_BCRYPT);
              $sessionUser->password = $hash;
            }

            if ($updated === true) {
              $sessionUser->save();
            }

            $return_success = true;

          } else {
            $return_confirm = $container->translator->trans('auth.validation.match');
            $return_success = false;
          }

        } else {
          $return_new = $container->translator->trans('auth.validation.minChar', [ '%number%' => '8' ]);
          $return_success = false;
        }

      } else {
        $return_current = $container->translator->trans('auth.validation.passwordIncorrect');
        $return_success = false;
      }

    } else {
      if (empty($current)) {
        $return_current = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
      if (empty($new)) {
        $return_new = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
      if (empty($confirm)) {
        $return_confirm = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
    }

    if ($return_current) {
      $container->flash->addMessage('error_currentPassword', $return_current);
    }

    if ($return_new) {
      $container->flash->addMessage('error_newPassword', $return_new);
    }

    if ($return_confirm) {
      $container->flash->addMessage('error_confirmPassword', $return_confirm);
    }

    return $return_success;

  }

  public function updatePersonalDetails($request, $container) {

    $return_firstname = null;
    $return_lastname = null;
    $return_email = null;
    $return_success = false;
    $updated = false;

    $firstname = trim($request->getParam('firstname'));
    $lastname = trim($request->getParam('lastname'));
    $email = trim($request->getParam('email'));

    if (!empty($firstname) && !empty($lastname) && !empty($email)) {

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $sessionUser = $container->AuthHelper->getSessionUser();
        $checkEmail = User::where('email', $email)->first();

        if (!$checkEmail || $email == $sessionUser->email) {

          if ($sessionUser->firstname != $firstname) {
            $sessionUser->firstname = $firstname;
            $updated = true;
          }

          if ($sessionUser->lastname != $lastname) {
            $sessionUser->lastname = $lastname;
            $updated = true;
          }

          if ($sessionUser->email != $email) {
            $sessionUser->email = $email;
            $updated = true;
          }

          if ($updated === true) {
            $sessionUser->save();
          }

          $return_success = true;

        } else {
          $return_email = $container->translator->trans('auth.validation.usedEmail');
          $return_success = false;
        }

      } else {
        $return_email = $container->translator->trans('auth.validation.invalidEmail');
        $return_success = false;
      }

    } else {
      if (empty($firstname)) {
        $return_firstname = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
      if (empty($lastname)) {
        $return_lastname = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
      if (empty($email)) {
        $return_email = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
    }

    if ($return_firstname) {
      $container->flash->addMessage('error_firstname', $return_firstname);
    }

    if ($return_lastname) {
      $container->flash->addMessage('error_lastname', $return_lastname);
    }

    if ($return_email) {
      $container->flash->addMessage('error_email', $return_email);
    }

    return $return_success;

  }

}
