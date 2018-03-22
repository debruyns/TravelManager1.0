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
  $this->post('/auth/signup', 'AuthController:postSignUp');

  // Recovery Routes
  $this->get('/auth/recovery', 'AuthController:getRecovery')->setName('auth.recovery');
  $this->post('/auth/recovery', 'AuthController:postRecovery');

  // Reset Routes
  $this->get('/auth/reset/{code}', 'AuthController:getReset')->setName('auth.reset');
  $this->post('/auth/reset', 'AuthController:postReset')->setName('auth.reset.post');

  // Activate Routes
  $this->get('/auth/activate/{code}', 'AuthController:getActivate')->setName('auth.activate');

})->add(new GuestRouteMiddleware($container));

// Routes that are not accessible when not logged in
$app->group('', function() {

  // Dashboard Routes
  $this->get('/dashboard', 'DashboardController:getDashboard')->setName('dashboard');

  // Logout Routes
  $this->get('/auth/logout', 'AuthController:getLogout')->setName('auth.logout');

  // Account Routes
  $this->get('/account', 'AccountController:getAccount')->setName('account');
  $this->post('/account/details', 'AccountController:postAccountDetails')->setName('account.details');
  $this->post('/account/password', 'AccountController:postPassword')->setName('account.password');
  $this->post('/account/language', 'AccountController:postLanguage')->setName('account.language');
  $this->post('/account/premium', 'AccountController:postPremium')->setName('account.premium');
  $this->post('/account/twofactor/activate', 'AccountController:postTwofactorActivate')->setName('account.twofactor.activate');
  $this->post('/account/twofactor/deactivate', 'AccountController:postTwofactorDeactivate')->setName('account.twofactor.deactivate');

  // Trip Routes
  $this->get('/trips', 'TripController:getTrips')->setName('trips');
  $this->get('/trips/create', 'TripController:getCreateTrip')->setName('trips.create');
  $this->post('/trips/create', 'TripController:postCreateTrip');
  $this->get('/trips/manage/{id}', 'TripController:getManageTrip')->setName('trips.manage');
  $this->post('/trips/manage/general', 'TripController:postUpdateGeneral')->setName('trips.manage.general');
  $this->get('/trips/manage/travelers/{id}', 'TripController:getManageTravelers')->setName('trips.travelers');
  $this->get('/trips/manage/travelers/edit/{id}/{traveler}', 'TripController:getEditTraveler')->setName('trips.travelers.edit');
  $this->post('/trips/manage/travelers/edit', 'TripController:postEditTraveler')->setName('trips.travelers.edit.post');
  $this->get('/trips/manage/travelers/delete/{id}/{traveler}', 'TripController:getDeleteTraveler')->setName('trips.travelers.delete');
  $this->post('/trips/manage/travelers/delete', 'TripController:postDeleteTraveler')->setName('trips.travelers.delete.post');
  $this->get('/trips/manage/travelers/create/{id}', 'TripController:getCreateTraveler')->setName('trips.travelers.create');
  $this->post('/trips/manage/travelers/create', 'TripController:postCreateTraveler')->setName('trips.travelers.create.post');


  // Payment Routes
  $this->get('/payment/token', 'PaymentController:getToken')->setName('payment.token');

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
