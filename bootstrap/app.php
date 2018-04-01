<?php

// Require the required namespaces
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Finder\Finder;
use Braintree\Configuration;
use Noodlehaus\Config;

// Start a session
session_start();

// Require the autoload functionality
require __DIR__ . '/../vendor/autoload.php';

// Create new app object
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ],
]);

// Get Container
$container = $app->getContainer();

// Config Setup
$container['config'] = function() {
  return new Config(__DIR__ . '/../config');
};

// Translator Setup
$container['translator'] = function($container) {

  $config = $container->config;

  if (isset($_SESSION['user_language'])) {
    $config->set('app.locale', $_SESSION['user_language']);
  } else {
    if (isset($_COOKIE['tm_language'])) {
      $config->set('app.locale', $_COOKIE['tm_language']);
    } else {
      $config->set('app.locale', 'en');
    }
  }

  $translator = new Translator($config->get('app.locale'));
  $translator->setFallbackLocales([$config->get('app.default_locale')]);
  $translator->addLoader('array', new ArrayLoader);

  $finder = new Finder;
  $langDirs = $finder->directories()->ignoreUnreadableDirs()->in(__DIR__ . '/../resources/lang');

  foreach ($langDirs as $dir) {
    $translator->addResource(
      'array',
      (new Config($dir))->all(),
      $dir->getRelativePathName()
    );
  }

  return $translator;

};

// Database Setup
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['config']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};


// Setup Flash
$container['flash'] = function($container) {
  return new \Slim\Flash\Messages;
};

// Auth Helper
$container['AuthHelper'] = function ($container) {
    return new \App\Helpers\AuthHelper;
};

// Trip Helper
$container['TripHelper'] = function ($container) {
    return new \App\Helpers\TripHelper;
};

// Account Helper
$container['AccountHelper'] = function ($container) {
    return new \App\Helpers\AccountHelper;
};

// Language Helper
$container['LanguageHelper'] = function ($container) {
    return new \App\Helpers\LanguageHelper;
};

// Currency Helper
$container['CurrencyHelper'] = function ($container) {
    return new \App\Helpers\CurrencyHelper;
};

// Setup Twig
$container['view'] = function ($container) {

    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views');

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->addExtension(new \App\Views\TranslateExtension(
        $container->translator
    ));

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    $view->getEnvironment()->addGlobal('session', [
      'check' => $container->AuthHelper->checkSession(),
      'user'  => $container->AuthHelper->getSessionUser(),
      'twofactor' => $container->AuthHelper->checkTwoFactor(),
      'premium' => [
        'check' => $container->AuthHelper->checkPremium(),
        'date' => $container->AuthHelper->premiumDate()
      ]
    ]);

    $view->getEnvironment()->addGlobal('language', [
      'active' => $container->LanguageHelper->getActiveLanguage($container),
      'code' => $container->config->get('app.locale'),
      'list' => $container->LanguageHelper->getList($container)
    ]);

    $view->getEnvironment()->addGlobal('system', [
      'date' => date('d/m/Y'),
    ]);

    return $view;

};

// Mail functionality
$container['mail'] = function ($container) {

    $config = null;
    if ($_SERVER['SERVER_ADDR'] == '159.89.12.109' || $_SERVER['SERVER_ADDR'] == '159.89.10.62'){
      $config = $container->config['mail'];
    } else {
      $config = $container->config['devmail'];
    }

    $transport = (new Swift_SmtpTransport($config['host'], $config['port']))
        ->setUsername($config['username'])
        ->setPassword($config['password']);

    $swift = new Swift_Mailer($transport);

    return (new App\Mail\Mailer\Mailer($swift, $container->view))
        ->alwaysFrom($config['from']['address'], $config['from']['name']);
};

// Setup Home Controller
$container['HomeController'] = function ($container) {
    return new \App\Controllers\HomeController($container);
};

// Setup Auth Controller
$container['AuthController'] = function ($container) {
    return new \App\Controllers\AuthController($container);
};

// Setup Account Controller
$container['AccountController'] = function ($container) {
    return new \App\Controllers\AccountController($container);
};

// Setup Trip Controller
$container['TripController'] = function ($container) {
    return new \App\Controllers\TripController($container);
};

// Setup Account Controller
$container['PaymentController'] = function ($container) {
    return new \App\Controllers\PaymentController($container);
};

// Setup Language Controller
$container['LanguageController'] = function ($container) {
    return new \App\Controllers\LanguageController($container);
};

// Setup Dashboard Controller
$container['DashboardController'] = function ($container) {
    return new \App\Controllers\DashboardController($container);
};

// Setup 404 page
$container['notFoundHandler'] = function ($container) {
  return function ($request, $response) use ($container) {
    $viewData = [
      'page' => [
        'title' => $container->translator->trans('error.404.pageTitle')
      ]
    ];
    $container->view->render($response, 'errors/404.twig', $viewData);
    return $response->withStatus(404);
  };
};

// Setup CSRF
$container['csrf'] = function() {
    return new \Slim\Csrf\Guard;
};
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

// Persist Input Data
$app->add(new \App\Middleware\PersistInputMiddleware($container));

// Braintree Payments
$pay = null;
if ($_SERVER['SERVER_ADDR'] == '159.89.12.109') {
  //$pay = $container->config['devpay'];
} else {
  $pay = $container->config['devpay'];
}
Configuration::environment($pay['environment']);
Configuration::merchantId($pay['merchant']);
Configuration::publicKey($pay['public']);
Configuration::privateKey($pay['private']);

// Require the routes
require __DIR__ . '/../app/routes.php';
