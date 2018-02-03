<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Payment;
use App\Middleware\AuthenticatorMiddleware;
use Braintree\Transaction;
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

  public function getTwofactor($container) {
    $user = $container->AuthHelper->getSessionUser();
    $auth = new AuthenticatorMiddleware();
    $secret = $auth->generateRandomSecret();
    $QR = $auth->getQR($user->email, $secret, "TravelManager");
    return [
      'secret' => $secret,
      'qr' => $QR
    ];
  }

  public function getPlans($container) {
    $return = "";
    $plans = Membership::where('active', 'true')->orderBy('days', 'ASC')->get();
    foreach ($plans as $plan) {
      $return .= '<option value="'.$plan->id.'">'.$container->translator->trans('account.premium.option', [ '%days%' => $plan->days, '%price%' => number_format($plan->price, 2, '.', '') ]).'</option>';
    }
    return $return;
  }

  public function activateTwofactor($request, $container) {

    $user = $container->AuthHelper->getSessionUser();
    if (empty($user->twofactor)) {

      $auth = new AuthenticatorMiddleware();
      if ($auth->verifyCode($request->getParam('secret'), $request->getParam('code'), 1)) {
        $user->twofactor = $request->getParam('secret');
        $user->save();
        return true;
      } else {
        $container->flash->addMessage('error_twofactor', $container->translator->trans('auth.validation.verifyCode'));
        $container->flash->addMessage('twofactor_secret', $request->getParam('secret'));
        $container->flash->addMessage('twofactor_qr', $auth->getQR($user->email, $request->getParam('secret'), "TravelManager"));
        return false;
      }
    } else {
      $container->flash->addMessage('error', $container->translator->trans('auth.validation.error'));
      return false;
    }

  }

  public function deactivateTwofactor($request, $container) {

    $user = $container->AuthHelper->getSessionUser();
    if (!empty($user->twofactor)) {

      $auth = new AuthenticatorMiddleware();
      if ($auth->verifyCode($user->twofactor, $request->getParam('code'), 1)) {
        $user->twofactor = null;
        $user->save();
        return true;
      } else {
        $container->flash->addMessage('error_twofactor', $container->translator->trans('auth.validation.verifyCode'));
        return false;
      }
    } else {
      $container->flash->addMessage('error', $container->translator->trans('auth.validation.error'));
      return false;
    }

  }

  public function buyPremium($request, $container) {

    if ($request->getParam('nonce')) {

      $membership = Membership::find($request->getParam('plan'));
      if ($membership) {

        if ($membership->active == 'true'){

          $payment = Transaction::sale([
            'amount' => $membership->price,
            'paymentMethodNonce' => $request->getParam('nonce'),
            'options' => [
              'submitForSettlement' => true
            ]
          ]);


          if ($payment->success) {

            $user = $container->AuthHelper->getSessionUser();
            $start = null;
            if ($container->AuthHelper->checkPremium()) {
              $start = $user->premium;
            } else {
              $start = date('Y-m-d');
            }
            $newPremium = date('Y-m-d', strtotime($start . ' + ' . $membership->days . ' days'));

            $receipt = Payment::create([
              'transaction' => $payment->transaction->id,
              'user' => $user->id,
              'previous' => $user->premium,
              'new' => $newPremium,
              'days' => $membership->days
            ]);

            $user->premium = $newPremium;
            $user->save();

            if ($receipt) {

              $container->flash->addMessage('success-heading', $container->translator->trans('account.premium.thanks'));
              $container->flash->addMessage('success', $container->translator->trans('account.premium.complete', [ '%date%' => date('d-m-Y', strtotime($newPremium)) ]));
              return true;

            } else {
              Transaction::void($payment->transaction->id);
            }

          } else {
            $container->flash->addMessage('error', $container->translator->trans('account.premium.noPayment'));
            return false;
          }

        } else {
          $container->flash->addMessage('error_plan', $container->translator->trans('account.premium.invalidPlan'));
          return false;
        }

      } else {
        $container->flash->addMessage('error_plan', $container->translator->trans('account.premium.invalidPlan'));
        return false;
      }

    } else {
      return false;
    }

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
