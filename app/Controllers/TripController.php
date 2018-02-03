<?php

namespace App\Controllers;

use Slim\Views\Twig as View;

class TripController extends Controller {

  // HTTP GET Sign In
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

    $viewData = [
      'page' => [
        'title' => $this->translator->trans('trips.create.pageTitle')
      ]
    ];

    return $this->view->render($response, 'trips/create.twig', $viewData);

  }

  public function postCreateTrip($request, $response) {

    echo $request->getParam('type');
    die();

  }

}