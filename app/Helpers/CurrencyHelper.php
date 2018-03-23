<?php

namespace App\Helpers;

use App\Models\Currency;

class CurrencyHelper {

  public function getCurrencies() {

     return Currency::orderBy('code', 'ASC')->get();

  }

  public function getCurrency($id) {

    return Currency::find($id);

  }

}
