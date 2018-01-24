<?php

// Require the required namespaces
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Finder\Finder;
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

// Language Helper
$container['LanguageHelper'] = function ($container) {
    return new \App\Helpers\LanguageHelper;
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
      'twofactor' => $container->AuthHelper->checkTwoFactor()
    ]);

    $view->getEnvironment()->addGlobal('language', [
      'active' => $container->LanguageHelper->getActiveLanguage($container),
      'list' => $container->LanguageHelper->getList($container)
    ]);

    return $view;

};

// Mail functionality
$container['mail'] = function ($container) {
    $config = $container->config['mail'];

    $transport = (Swift_SmtpTransport::newInstance($config['host'], $config['port']))
        ->setUsername($config['username'])
        ->setPassword($config['password']);

    $swift = Swift_Mailer::newInstance($transport);

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

// Setup Language Controller
$container['LanguageController'] = function ($container) {
    return new \App\Controllers\LanguageController($container);
};

// Setup Dashboard Controller
$container['DashboardController'] = function ($container) {
    return new \App\Controllers\DashboardController($container);
};

// Setup CSRF
$container['csrf'] = function() {
    return new \Slim\Csrf\Guard;
};
$app->add(new \App\Middleware\CsrfViewMiddleware($container));
$app->add($container->csrf);

// Persist Input Data
$app->add(new \App\Middleware\PersistInputMiddleware($container));


// Require the routes
require __DIR__ . '/../app/routes.php';
