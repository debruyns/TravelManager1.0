<?php

namespace App\Helpers;

use App\Models\Language;

class LanguageHelper {

  public function getActiveLanguage($container) {

    $active = Language::where('code', $container->config->get('app.locale'))->first();
    if ($active) {
      return $active->name;
    }

    return "";

  }

  public function getList($container) {

    $languages = Language::where('active', 'true')->where('code', '!=', $container->config->get('app.locale'))->orderBy('name', 'ASC')->get();

    $return = "";
    $count_languages = count($languages);
    $count_loops = 0;

    foreach ($languages as $language) {
      $count_loops++;

      $return .= "<a href='".$container->router->PathFor('language.change', [ 'code' => $language->code ])."'>".$language->name."</a>";

      if ($count_loops < $count_languages) {
        $return .= "<br />";
      }

    }

    return $return;

  }

}
