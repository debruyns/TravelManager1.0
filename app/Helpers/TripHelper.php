<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Shared;
use DateTime;

class TripHelper {

  public function getTrip($id, $container) {

    $identifier = ( ( (int) substr($id, 10) - 25879 ) / 5 );

    $trip = Trip::find($identifier);

    $sessionUser = $container->AuthHelper->getSessionUser();
    if ($sessionUser) {

      $trip->formatStartDate = date('d/m/Y', strtotime($trip->start));
      $trip->formatStopDate = date('d/m/Y', strtotime($trip->stop));

      if ($trip->owner == $sessionUser->id) {

        // Add Identifier
        $trip->identifier = 'CTO-'.strtoupper(substr(md5($trip->owner), 0, 5)).'-'.(($trip->id*5)+25879);

        return $trip;

      } else {
        return null;
      }

    } else {
      return null;
    }

  }

  public function createTrip($request, $container) {

    $return_result = false;
    $error_name = null;
    $error_period = null;
    $error_type = null;

    $name = $request->getParam('name');
    $period = $request->getParam('period');
    $type = $request->getParam('type');

    if (!empty($name) && !empty($period) && !empty($type)) {

      if (strlen($name) <= 20) {

        $period_split = explode(' - ', $period);
        if (count($period_split) == 2){

          $start_split = explode('/', $period_split[0]);
          if (count($start_split) == 3) {

            if (checkdate($start_split[1], $start_split[0], $start_split[2])) {

              $stop_split = explode('/', $period_split[1]);
              if (count($stop_split) == 3) {

                if (checkdate($stop_split[1], $stop_split[0], $stop_split[2])) {

                  if (new DateTime($start_split[2]."-".$start_split[1]."-".$start_split[0]) >= new DateTime(date('Y-m-d'))) {

                    if (new DateTime($start_split[2]."-".$start_split[1]."-".$start_split[0]) <= new DateTime($stop_split[2]."-".$stop_split[1]."-".$stop_split[0])) {

                      if ($type == 'leisure' || $type == 'business') {

                        $user = $container->AuthHelper->getSessionUser();

                        $new_trip = Trip::create([
                                      'name' => $name,
                                      'start' => $start_split[2]."-".$start_split[1]."-".$start_split[0],
                                      'stop' => $stop_split[2]."-".$stop_split[1]."-".$stop_split[0],
                                      'active' => 'true',
                                      'owner' => $user->id,
                                      'type' => $type,
                                      'phase' => 1
                                    ]);

                        if ($new_trip) {
                          $container->flash->addMessage('success', $container->translator->trans('trips.create.success', [ '%name%' => $new_trip->name ]));
                          return true;
                        } else {
                          $error_general = $container->translator->trans('auth.validation.error');
                        }

                      } else {
                        $error_type = $container->translator->trans('auth.validation.invalid');
                      }

                    } else {
                      $error_period = $container->translator->trans('auth.validation.pastStart');
                    }

                  } else {
                    $error_period = $container->translator->trans('auth.validation.pastDate');
                  }

                } else {
                  $error_period = $container->translator->trans('auth.validation.invalidDate');
                }

              } else {
                $error_period = $container->translator->trans('auth.validation.invalidDate');
              }

            } else {
              $error_period = $container->translator->trans('auth.validation.invalidDate');
            }

          } else {
            $error_period = $container->translator->trans('auth.validation.invalidDate');
          }

        } else {
          $error_period = $container->translator->trans('auth.validation.invalidDate');
        }

      } else {
        $error_name = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '20' ]);
      }

    } else {
      if (empty($name)) {
        $error_name = $container->translator->trans('auth.validation.required');
      }
      if (empty($period)) {
        $error_period = $container->translator->trans('auth.validation.required');
      }
      if (empty($type)) {
        $error_type = $container->translator->trans('auth.validation.required');
      }
    }

    if ($error_name) {
      $container->flash->addMessage('error_name', $error_name);
    }

    if ($error_period) {
      $container->flash->addMessage('error_period', $error_period);
    }

    if ($error_type) {
      $container->flash->addMessage('error_type', $error_type);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

    return $return_result;

  }

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

      // Add Identifier
      $trip->identifier = 'CTO-'.strtoupper(substr(md5($user->id), 0, 5)).'-'.(($trip->id*5)+25879);

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

        // Add Identifier
        $myshare->identifier = 'CTO-'.strtoupper(substr(md5($owner->id), 0, 5)).'-'.(($myshare->id*5)+25879);
      }

    }

    return $myshared;

  }

}
