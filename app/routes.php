<?php

use App\Middleware\AuthRouteMiddleware;
use App\Middleware\GuestRouteMiddleware;
use App\Middleware\TwoFactorRouteMiddleware;

// Routes that are not accessible when logged in
$app->group('', function() {

  // Sign In Routes
  $this->get('/auth/signin', 'AuthController:getSignIn')->setName('auth.signin');
  $this->post('/auth/signin', 'AuthController:postSignIn');

  // Sign Up Routes
  $this->get('/auth/signup', 'AuthController:getSignUp')->setName('auth.signup');

  // Recovery Routes
  $this->get('/auth/recovery', 'AuthController:getLogin')->setName('auth.recovery');

})->add(new GuestRouteMiddleware($container));

// Routes that are not accessible when not logged in
$app->group('', function() {

  // Dashboard Routes
  $this->get('/dashboard', 'DashboardController:getDashboard')->setName('dashboard');

  // Logout Routes
  $this->get('/auth/logout', 'AuthController:getLogout')->setName('auth.logout');

})->add(new AuthRouteMiddleware($container));

// Routes that are accessible when two factor verification is active
$app->group('', function() {

  // Two-Factor Authentication Routes
  $this->get('/auth/verify', 'AuthController:getVerify')->setName('auth.verify');
  $this->post('/auth/verify', 'AuthController:postVerify');

  // Two-Factor Cancel routes
  $this->get('/auth/cancel', 'AuthController:getCancel')->setName('auth.cancel');

})->add(new TwoFactorRouteMiddleware($container));

// Routes that are always accessible
$app->get('/language/{code}', 'LanguageController:getLanguage')->setName('language.change');
$app->get('/', 'HomeController:getIndex')->setName('home');
