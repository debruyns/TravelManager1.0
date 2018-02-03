<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class AccountController extends Controller {

  // HTTP GET Account
  public function getAccount($request, $response) {

    $languages = $this->AccountHelper->getLanguages($this);
    $plans = $this->AccountHelper->getPlans($this);
    $twofactor = $this->AccountHelper->getTwofactor($this);

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('account.account.pageTitle')
      ],
      'languages' => $languages,
      'plans' => $plans,
      'twofactor' => $twofactor
    ];

    return $this->view->render($response, 'account/account.twig', $viewData);

  }

  // HTTP Post Account Details
  public function postAccountDetails($request, $response) {

    $result = $this->AccountHelper->updatePersonalDetails($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_details', 'true');
    } else {
      $this->flash->addMessage('success', $this->translator->trans('account.details.success'));
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

  // HTTP Post Password
  public function postPassword($request, $response) {

    $result = $this->AccountHelper->updatePassword($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_password', 'true');
    } else {
      $this->flash->addMessage('success', $this->translator->trans('account.password.success'));
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

  // HTTP Post Password
  public function postLanguage($request, $response) {

    $result = $this->AccountHelper->updateLanguage($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_language', 'true');
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

  // HTTP Post Premium
  public function postPremium($request, $response) {

    $result = $this->AccountHelper->buyPremium($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_premium', 'true');
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

  // HTTP Post Two Factor Activate
  public function postTwofactorActivate($request, $response) {

    $result = $this->AccountHelper->activateTwofactor($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_twofactor', 'true');
    } else {
      $this->flash->addMessage('success', $this->translator->trans('account.twofactor.success'));
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

  // HTTP Post Two Factor Deactivate
  public function postTwofactorDeactivate($request, $response) {

    $result = $this->AccountHelper->deactivateTwofactor($request, $this);
    if (!$result) {
      $this->flash->addMessage('account_twofactor', 'true');
    } else {
      $this->flash->addMessage('success', $this->translator->trans('account.twofactor.successDeactivate'));
    }
    return $response->withRedirect($this->router->pathFor('account'));

  }

}
