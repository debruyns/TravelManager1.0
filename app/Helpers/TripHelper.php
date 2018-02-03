<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Shared;
use DateTime;

class TripHelper {

  public function getMyTrips($container) {

    $user = $container->AuthHelper->getSessionUser();
    $trips = Trip::where('owner', $user->id)->orderBy('active', 'ASC')->get();

    foreach ($trips as &$trip) {

      // Check if the trip is shared
      if (Shared::where('trip', $trip->id)->count() > 0) {
        $trip->shared = true;
      } else {
        $trip->shared = false;
      }

      // Add user to trip
      $trip->user = $user;

    }

    return $trips;

  }

  public function getMySharedTrips($container) {

    $myshared = array();

    $user = $container->AuthHelper->getSessionUser();
    $shared = Shared::where('user', $user->id)->get();

    foreach ($shared as $share) {

      $trip = Trip::find($share->trip);
      if ($trip) {

        if ($share->readonly == 'true') {
          $trip->readonly = true;
        } else {
          $trip->readonly = false;
        }

        $myshared[] = $trip;

      }

    }

    foreach ($myshared as &$myshare) {

      $owner = User::find($myshare->owner);
      if ($owner) {
        $myshare->owner = $owner;
      }

    }

    return $myshared;

  }

}
