<?php

return [

  'app' => [

    'locale' => 'en',
    'default_locale' => 'en'

  ],

  'db' => [
    'driver' => 'mysql',
    'host' => 'dev.citytakeoff.com',
    'database' => 'travelmanager',
    'username' => 'travelmanager',
    'password' => 'ctoTM2018@#',
    'charset' => 'utf8',
    'collation' => 'utf8_general_ci',
    'prefix' => '',
  ],

  'devmail' => [
    'host' => 'smtp.mailtrap.io',
    'port' => '2525',
    'from' => [
      'name' => 'CityTakeOff',
      'address' => 'noreply@citytakeoff.com'
    ],
    'username' => '6cfb009fa51bfd',
    'password' => '28ac84c9b8288d'
  ],

  'mail' => [
    'host' => 'smtp.mailgun.org',
    'port' => '2525',
    'from' => [
      'name' => 'CityTakeOff',
      'address' => 'noreply@citytakeoff.com'
    ],
    'username' => 'postmaster@citytakeoff.com',
    'password' => '8641984954003ecefd68a5dd01936d90'
  ],

  'devpay' => [
    'environment' => 'sandbox',
    'merchant' => 'mbc2kjh4pdqf3xht',
    'public' => 'vcgk7j7tpzrp5238',
    'private' => 'a8c8a20106cbf85a269595ab936d2286'
  ]

];
