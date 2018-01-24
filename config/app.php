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

  'mail' => [
    'host' => 'smtp.mailtrap.io',
    'port' => '25',
    'from' => [
      'name' => 'CityTakeOff',
      'address' => 'noreply@citytakeoff.com'
    ],
    'username' => '6cfb009fa51bfd',
    'password' => '28ac84c9b8288d'
  ]


];
