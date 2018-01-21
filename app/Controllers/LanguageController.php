<?php

namespace App\Controllers;

use Slim\Views\Twig as View;
use App\Models\Language;

class LanguageController extends Controller {

  // HTTP GET Sign In
  public function getLanguage($request, $response, $args) {

    if (isset($request->getHeader('HTTP_REFERER')[0])){
      $previous_url = $request->getHeader('HTTP_REFERER')[0];
    } else {
      $previous_url = null;
    }
    $current_url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $this->router->PathFor('language.change', [ 'code' => $args['code'] ]);

    $selected = $args['code'];
    $language = Language::where('code', $selected)->first();

    if ($language) {
      $_SESSION['user_language'] = $language->code;
      setcookie('tm_language', $language->code, time() + (86400 * 30), '/');
    }

    if ($previous_url != $current_url && $previous_url != null) {
      return $response->withRedirect($previous_url);
    } else {
      return $response->withRedirect($this->router->pathFor('auth.signin'));
    }

  }

}
