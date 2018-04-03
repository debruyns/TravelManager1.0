<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class TripController extends Controller {

  // HTTP GET Trips
  public function getTrips($request, $response) {

    $myTrips = $this->TripHelper->getMyTrips($this);
    $myShared = $this->TripHelper->getMySharedTrips($this);

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('trips.general.pageTitle')
      ],
      'mytrips' => $myTrips,
      'myshared' => $myShared
    ];

    return $this->view->render($response, 'trips/trips.twig', $viewData);

  }

  // HTTP GET Trip Create
  public function getCreateTrip($request, $response) {

    $currencies = $this->CurrencyHelper->getCurrencies();

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('trips.create.pageTitle')
      ],
      'currencies' => $currencies
    ];

    return $this->view->render($response, 'trips/create.twig', $viewData);

  }

  public function postCreateTrip($request, $response) {

    $return = $this->TripHelper->createTrip($request, $this);

    if ($return == true) {
      return $response->withRedirect($this->router->pathFor('trips'));
    } else {
      return $response->withRedirect($this->router->pathFor('trips.create'));
    }

  }

  // HTTP GET Manage Trip
  public function getManageTrip($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);
    $currencies = $this->CurrencyHelper->getCurrencies();

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'active' => 'general',
      'trip' => $trip,
      'currencies' => $currencies
    ];

    return $this->view->render($response, 'trips/manage.twig', $viewData);

  }

  public function postUpdateGeneral($request, $response) {

    $this->TripHelper->updateGeneral($request, $this);
    return $response->withRedirect($this->router->pathFor('trips.manage', [
      'id' => $request->getParam('identifier')
    ]));

  }

  // HTTP GET Travelers Trip
  public function getManageTravelers($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $travelers = $this->TripHelper->getTravelers($trip->id, $this);

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'active' => 'travelers',
      'trip' => $trip,
      'travelers' => $travelers
    ];

    return $this->view->render($response, 'trips/travelers.twig', $viewData);

  }

  // HTTP GET Details Traveler
  public function getDetailsTraveler($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $traveler = $this->TripHelper->getTraveler($args['traveler'], $trip->id, $this);
    if (!$traveler) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip,
      'traveler' => $traveler
    ];

    return $this->view->render($response, 'trips/travelers/details.twig', $viewData);

  }

  // HTTP GET Create Traveler
  public function getCreateTraveler($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip
    ];

    return $this->view->render($response, 'trips/travelers/create.twig', $viewData);

  }

  // HTTP POST Create Traveler
  public function postCreateTraveler($request, $response) {

    $return = $this->TripHelper->createTraveler($request, $this);

    if ($return == true) {
      return $response->withRedirect($this->router->pathFor('trips.travelers', [
        'id' => $request->getParam('identifier')
      ]));
    } else {
      return $response->withRedirect($this->router->pathFor('trips.travelers.create', [
        'id' => $request->getParam('identifier')
      ]));
    }

  }

  // HTTP GET Edit Traveler
  public function getEditTraveler($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $traveler = $this->TripHelper->getTraveler($args['traveler'], $trip->id, $this);
    if (!$traveler) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip,
      'traveler' => $traveler
    ];

    return $this->view->render($response, 'trips/travelers/edit.twig', $viewData);

  }

  // HTTP POST Edit Traveler
  public function postEditTraveler($request, $response) {

    $return = $this->TripHelper->editTraveler($request, $this);

    if ($return == true) {
      return $response->withRedirect($this->router->pathFor('trips.travelers.details', [
        'id' => $request->getParam('identifier'),
        'traveler' => $request->getParam('traveler')
      ]));
    } else {
      return $response->withRedirect($this->router->pathFor('trips.travelers.edit', [
        'id' => $request->getParam('identifier'),
        'traveler' => $request->getParam('traveler')
      ]));
    }

  }

  // HTTP GET Delete Traveler
  public function getDeleteTraveler($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $traveler = $this->TripHelper->getTraveler($args['traveler'], $trip->id, $this);
    if (!$traveler) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip,
      'traveler' => $traveler
    ];

    return $this->view->render($response, 'trips/travelers/delete.twig', $viewData);

  }

  // HTTP POST Delete Traveler
  public function postDeleteTraveler($request, $response) {

    $return = $this->TripHelper->deleteTraveler($request, $this);

    if ($return == true) {
      return $response->withRedirect($this->router->pathFor('trips.travelers', [
        'id' => $request->getParam('identifier')
      ]));
    } else {
      return $response->withRedirect($this->router->pathFor('trips.travelers.edit', [
        'id' => $request->getParam('identifier'),
        'traveler' => $request->getParam('traveler')
      ]));
    }

  }

  // HTTP GET Accommodations Trip
  public function getManageAccommodations($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $accommodations = $this->TripHelper->getAccommodations($trip->id, $this);

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'active' => 'accommodations',
      'trip' => $trip,
      'accommodations' => $accommodations
    ];

    return $this->view->render($response, 'trips/accommodations.twig', $viewData);

  }

  // HTTP GET Details Accommodation
  public function getDetailsAccommodation($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $accommodation = $this->TripHelper->getAccommodation($args['accommodation'], $trip->id, $this);
    if (!$accommodation) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip,
      'accommodation' => $accommodation
    ];

    return $this->view->render($response, 'trips/accommodations/details.twig', $viewData);

  }

  // HTTP GET Create Accommodation
  public function getCreateAccommodation($request, $response, $args) {

    $trip = $this->TripHelper->getTrip($args['id'], $this);

    if (!$trip) {
      $this->flash->addMessage('error', $this->translator->trans('trips.manage.notFound'));
      return $response->withRedirect($this->router->pathFor('trips'));
    }

    $viewData = [
      'page' => [
        'title' => $trip->name
      ],
      'trip' => $trip
    ];

    return $this->view->render($response, 'trips/accommodations/create.twig', $viewData);

  }

  // HTTP POST Create Accommodation
  public function postCreateAccommodation($request, $response) {

    $return = $this->TripHelper->createAccommodation($request, $this);

    if ($return == true) {
      return $response->withRedirect($this->router->pathFor('trips.accommodations', [
        'id' => $request->getParam('identifier')
      ]));
    } else {
      return $response->withRedirect($this->router->pathFor('trips.accommodations.create', [
        'id' => $request->getParam('identifier')
      ]));
    }

  }

}
