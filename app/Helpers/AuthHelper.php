<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Recovery;
use App\Middleware\AuthenticatorMiddleware;
use App\Mail\PasswordReset;
use App\Mail\Activate;
use DateTime;

class AuthHelper {

  public function getSessionUser() {
    if (isset($_SESSION['user_id'])) {
      return User::find($_SESSION['user_id']);
    } else {
      return null;
    }
  }

  public function checkPremium() {
    $premiumStatus = $this->getSessionUser()->premium;
    if (!empty($premiumStatus)) {
      $now = strtotime(date('Y-m-d'));
      $premium = strtotime($premiumStatus);
      if ($premium >= $now) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function premiumDate() {
    return date('d-m-Y', strtotime($this->getSessionUser()->premium));
  }

  public function checkSession() {
    return isset($_SESSION['user_id']);
  }

  public function checkTwoFactor() {
    return isset($_SESSION['user_twofactor']);
  }

  public function checkReset($code, $container) {
    $recovery = Recovery::where('code', $code)->first();
    if ($recovery) {
      $now = new DateTime(Date('Y-m-d H:i:s'));
      $created = new DateTime($recovery->created_at);
      $interval = $now->getTimestamp() - $created->getTimestamp();
      if ($interval > 86400) {
        return false;
      }
    } else {
      return false;
    }
    return true;
  }

  public function userActivate($code, $container) {

    $user = User::where('secret', $code)->first();
    if ($user) {

      if ($user->active == 'false') {

        $user->active = 'true';
        $user->save();
        $container->flash->addMessage('success', $container->translator->trans('auth.activate.success'));

      }

    }

  }

  public function userSignUp($request, $container) {

    $return_firstname = null;
    $return_lastname = null;
    $return_email = null;
    $return_password = null;
    $return_general = null;
    $return_success = false;

    $firstname = trim($request->getParam('firstname'));
    $lastname = trim($request->getParam('lastname'));
    $email = trim($request->getParam('email'));
    $password = trim($request->getParam('password'));

    if (!empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {

      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        if (!User::where('email', $email)->first()) {

          if (strlen($password) >= 8) {

            $unique = false;
            $secret = null;
            while ($unique === false) {
              $secret = $this->generateRandomString();
              $checkUnique = User::where('secret', $secret)->first();
              if (!$checkUnique) {
                $unique = true;
              }
            }

            $hash = password_hash($password, PASSWORD_BCRYPT);

            $newUser = User::create([
                        'firstname' => $firstname,
                        'lastname' => $lastname,
                        'email' => $email,
                        'password' => $hash,
                        'secret' => $secret,
                        'language' => $container->config->get('app.locale'),
                        'active' => 'false',
                        'status' => 'normal',
                        'last_login' => null,
                        'premium' => null,
                        'twofactor' => null
                      ]);

            if ($newUser) {
              $container->mail->to($newUser->email, $newUser->firstname." ".$newUser->lastname)->send(new Activate($container->translator, $newUser, $secret));
              $container->flash->addMessage('success-heading', $container->translator->trans('auth.signup.successHeading'));
              $container->flash->addMessage('success', $container->translator->trans('auth.signup.successMessage', [
                '%email%' => $newUser->email
              ]));
              $return_success = true;
            } else {
              $container->flash->addMessage('error', $container->translator->trans('auth.validation.error'));
            }

          } else {
            $return_password = $container->translator->trans('auth.validation.minChar', [ '%number%' => '8' ]);
            $return_success = false;
          }

        } else {
          $return_success = false;
          $return_email = $container->translator->trans('auth.validation.usedEmail');
        }

      } else {
        $return_success = false;
        $return_email = $container->translator->trans('auth.validation.invalidEmail');
      }

    } else {
      if (empty($firstname)) {
        $return_success = false;
        $return_firstname = $container->translator->trans('auth.validation.required');
      }
      if (empty($lastname)) {
        $return_success = false;
        $return_lastname = $container->translator->trans('auth.validation.required');
      }
      if (empty($email)) {
        $return_success = false;
        $return_email = $container->translator->trans('auth.validation.required');
      }
      if (empty($password)) {
        $return_success = false;
        $return_password = $container->translator->trans('auth.validation.required');
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

    if ($return_password) {
      $container->flash->addMessage('error_password', $return_password);
    }

    if ($return_general) {
      $container->flash->addMessage('error', $return_general);
    }

    return $return_success;

  }

  public function userPasswordReset($request, $container) {

    $return_password = null;
    $return_confirm = null;
    $return_general = null;
    $return_success = false;

    $password = trim($request->getParam('password'));
    $confirm = trim($request->getParam('confirm'));
    $code = $request->getParam('resetcode');

    if (!empty($password) && !empty($confirm)) {

      if (strlen($password) >= 8) {

        if ($password == $confirm) {

          $recovery = Recovery::where('code', $code)->first();
          if ($recovery) {

            $user = User::find($recovery->user);
            if ($user) {

              $hash = password_hash($password, PASSWORD_BCRYPT);
              $user->password = $hash;
              $user->save();
              $recovery->delete();
              $return_success = true;
              $container->flash->addMessage('success', $container->translator->trans('auth.reset.success'));

            } else {
              $return_general = $container->translator->trans('auth.validation.error');
              $return_success = false;
            }

          } else {
            $return_general = $container->translator->trans('auth.reset.invalid');
            $return_success = false;
          }

        } else {
          $return_confirm = $container->translator->trans('auth.validation.match');
          $return_success = false;
        }

      } else {
        $return_password = $container->translator->trans('auth.validation.minChar', [ '%number%' => '8' ]);
        $return_success = false;
      }

    } else {
      if (empty($password)){
        $return_password = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
      if (empty($confirm)){
        $return_confirm = $container->translator->trans('auth.validation.required');
        $return_success = false;
      }
    }

    if ($return_password) {
      $container->flash->addMessage('error_password', $return_password);
    }

    if ($return_confirm) {
      $container->flash->addMessage('error_confirm', $return_confirm);
    }

    if ($return_general) {
      $container->flash->addMessage('error', $return_general);
    }

    return $return_success;

  }

  public function userRecovery($request, $container) {

    $email = trim($request->getParam('email'));
    $timeConstraint = false;

    if (!empty($email)) {

      $user = User::where('email', $email)->first();
      if ($user) {

        $lastRecovery = Recovery::where('user', $user->id)->orderBy('created_at', 'DESC')->first();

        if ($lastRecovery) {

          $now = new DateTime(Date('Y-m-d H:i:s'));
          $created = new DateTime($lastRecovery->created_at);
          $interval = $now->getTimestamp() - $created->getTimestamp();

          if ($interval > 300) {
            $timeConstraint = true;
          }

        } else {
          $timeConstraint = true;
        }

        if ($timeConstraint === true) {

          if ($user->active == 'true') {

            if ($user->status == 'normal') {

              $unique = false;
              $code = null;
              while ($unique === false) {
                $code = $this->generateRandomString();
                $checkUnique = Recovery::where('code', $code)->first();
                if (!$checkUnique) {
                  $unique = true;
                }
              }

              $newRecovery = Recovery::create([
                'code' => $code,
                'user' => $user->id
              ]);

              if ($newRecovery) {
                $container->mail->to($user->email, $user->firstname." ".$user->lastname)->send(new PasswordReset($container->translator, $user, $code));
                $container->flash->addMessage('success-heading', $container->translator->trans('auth.recovery.successHeading'));
                $container->flash->addMessage('success', $container->translator->trans('auth.recovery.successMessage', [
                  '%email%' => $user->email
                ]));
              } else {
                $container->flash->addMessage('error', $container->translator->trans('auth.validation.error'));
              }

            } else {
              $container->flash->addMessage('error', $container->translator->trans('auth.validation.wrongStatus'));
            }

          } else {
            $container->flash->addMessage('error', $container->translator->trans('auth.validation.notActive'));
          }

        } else {
          $container->flash->addMessage('error', $container->translator->trans('auth.validation.recoveryTime'));
        }

      } else {
        $container->flash->addMessage('error_email', $container->translator->trans('auth.validation.emailUnknown'));
      }

    } else {
      $container->flash->addMessage('error_email', $container->translator->trans('auth.validation.required'));
    }

  }

  public function userVerify($request, $container) {

    // Initialize return variables
    $return_success = false;

    $user = User::find($_SESSION['user_twofactor']);
    if ($user){
      $authenticator = new AuthenticatorMiddleware();
      if ($authenticator->verifyCode($user->twofactor, $request->getParam('code'), 1)) {

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

  private function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

}
