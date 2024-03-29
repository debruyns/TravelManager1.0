<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Trip;
use App\Models\Shared;
use App\Models\Traveler;
use App\Models\Accommodation;
use App\Models\Currency;
use DateTime;

class TripHelper {

  public function createAccommodation($request, $container) {

    $error_name = null;
    $error_period = null;
    $error_reference = null;
    $error_price = null;
    $error_meals = null;
    $error_cancelable = null;
    $error_cancelbefore = null;
    $error_general = null;

    $name = $request->getParam('name');
    $period = $request->getParam('period');
    $reference = $request->getParam('reference');
    $price = $request->getParam('price');
    $meals = $request->getParam('meals');
    $cancelable = $request->getParam('cancelable');
    $cancelbefore = $request->getParam('cancelbefore');

    $trip = $this->getTrip($request->getParam('identifier'));

    if ($trip) {

      if (!empty(trim($name)) && !empty($period) && !empty($price) && !empty($meals) && !empty($cancelable)) {

        if (strlen(trim($name)) <= 50) {

          $period_split = explode(' - ', $period);
          if (count($period_split) == 2){

            $start_split = explode('/', $period_split[0]);
            if (count($start_split) == 3) {

              if (checkdate($start_split[1], $start_split[0], $start_split[2])) {

                $stop_split = explode('/', $period_split[1]);
                if (count($stop_split) == 3) {

                  if (checkdate($stop_split[1], $stop_split[0], $stop_split[2])) {

                    //if (new DateTime())

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
          $error_name = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '50' ]);
        }

      } else {

        if (empty($name)) {
          $error_name = $container->translator->trans('auth.validation.required');
        }

        if (empty($period)) {
          $error_period = $container->translator->trans('auth.validation.required');
        }

        if (empty($price)) {
          $error_price = $container->translator->trans('auth.validation.required');
        }

        if (empty($meals)) {
          $error_meals = $container->translator->trans('auth.validation.required');
        }

        if (empty($cancelable)) {
          $error_name = $container->translator->trans('auth.validation.required');
        }

      }

    } else {
      $error_general = $container->translator->trans('trips.error.notFound');
    }

    if ($error_name) {
      $container->flash->addMessage('error_name', $error_name);
    }

    if ($error_period) {
      $container->flash->addMessage('error_period', $error_period);
    }

    if ($error_reference) {
      $container->flash->addMessage('error_reference', $error_reference);
    }

    if ($error_price) {
      $container->flash->addMessage('error_price', $error_price);
    }

    if ($error_meals) {
      $container->flash->addMessage('error_meals', $error_meals);
    }

    if ($error_cancelable) {
      $container->flash->addMessage('error_cancelable', $error_cancelable);
    }

    if ($error_cancelbefore) {
      $container->flash->addMessage('error_cancelbefore', $error_cancelbefore);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

    return false;

  }

  public function getAccommodation($accommodation, $trip, $container) {

    $accommodation_id = (int) $accommodation / 55;
    $accommodation_obj =  Accommodation::where('id', $accommodation_id)->where('trip', $trip)->first();

    if ($accommodation_obj) {
      $accommodation_obj->identifier = ( (int) $accommodation_obj->id * 55 );
      return $accommodation_obj;
    } else {
      return null;
    }

  }

  public function getAccommodations($id, $container) {

    $accommodations = Accommodation::where('trip', $id)->orderBy('checkin', 'ASC')->get();

    foreach ($accommodations as &$accommodation) {

      $accommodation->identifier = ( (int) $accommodation->id * 55 );
      $accommodation->period = date('d/m/Y', strtotime($accommodation->checkin)).' - '.date('d/m/Y', strtotime($accommodation->checkout));

    }

    return $accommodations;

  }

  public function deleteTraveler($request, $container) {

    $error_general = null;

    $identifier = ( ( (int) substr($request->getParam('identifier'), 10) - 25879 ) / 5 );
    $trip = Trip::find($identifier);
    $user = $container->AuthHelper->getSessionUser();

    $traveler_id = (int) $request->getParam('traveler') / 55;
    $traveler = Traveler::where('id', $traveler_id)->where('trip', $identifier)->get()->first();

    $shared = Shared::where('trip', $trip->id)->where('user', $user->id)->where('readonly', 'false')->get();
    if (count($shared) > 0) {
      $shared = true;
    } else {
      $shared = false;
    }

    if ($trip) {

      if ($traveler) {

        if ($trip->active == 'true') {

          if ($trip->owner == $user->id || $shared == true) {

            $traveler->delete();

            $container->flash->addMessage('success', $container->translator->trans('trips.travelers.deleted'));

            return true;

          } else {
            $error_general = $container->translator->trans('trips.error.auth');
          }

        } else {
          $error_general = $container->translator->trans('trips.error.notActive');
        }

      } else {
        $error_general = $container->translator->trans('trips.error.notFound');
      }

    } else {
      $error_general = $container->translator->trans('trips.error.notFound');
    }

    if ($error_firstname) {
      $container->flash->addMessage('error_firstname', $error_firstname);
    }

    if ($error_lastname) {
      $container->flash->addMessage('error_lastname', $error_lastname);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

    return false;

  }

  public function editTraveler($request, $container) {

    $error_firstname = null;
    $error_lastname = null;
    $error_general = null;

    $firstname = $request->getParam('firstname');
    $lastname = $request->getParam('lastname');

    $identifier = ( ( (int) substr($request->getParam('identifier'), 10) - 25879 ) / 5 );
    $trip = Trip::find($identifier);
    $user = $container->AuthHelper->getSessionUser();

    $traveler_id = (int) $request->getParam('traveler') / 55;
    $traveler = Traveler::where('id', $traveler_id)->where('trip', $identifier)->get()->first();

    $shared = Shared::where('trip', $trip->id)->where('user', $user->id)->where('readonly', 'false')->get();
    if (count($shared) > 0) {
      $shared = true;
    } else {
      $shared = false;
    }

    if ($trip) {

      if ($traveler) {

        if ($trip->active == 'true') {

          if ($trip->owner == $user->id || $shared == true) {

            if (!empty($firstname) && !empty($lastname)) {

              if (strlen($firstname) <= 40 && strlen($lastname) <= 40) {

                $searchDuplicate = Traveler::where('trip', $trip->id)->where('firstname', $firstname)->where('lastname', $lastname)->where('id', '!=', $traveler->id)->get();
                if (count($searchDuplicate) == 0) {

                  $traveler->firstname = $firstname;
                  $traveler->lastname = $lastname;
                  $traveler->save();

                  $container->flash->addMessage('success', $container->translator->trans('trips.travelers.changed'));
                  return true;


                } else {
                  $error_general = $container->translator->trans('trips.travelers.duplicate');
                }

              } else {

                if (strlen($firstname) > 40) {
                  $error_firstname = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '40' ]);
                }
                if (strlen($lastname) > 40) {
                  $error_lastname = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '40' ]);
                }

              }

            } else {

              if (empty($firstname)) {
                $error_firstname = $container->translator->trans('auth.validation.required');
              }
              if (empty($lastname)) {
                $error_lastname = $container->translator->trans('auth.validation.required');
              }

            }

          } else {
            $error_general = $container->translator->trans('trips.error.auth');
          }

        } else {
          $error_general = $container->translator->trans('trips.error.notActive');
        }

      } else {
        $error_general = $container->translator->trans('trips.error.notFound');
      }

    } else {
      $error_general = $container->translator->trans('trips.error.notFound');
    }

    if ($error_firstname) {
      $container->flash->addMessage('error_firstname', $error_firstname);
    }

    if ($error_lastname) {
      $container->flash->addMessage('error_lastname', $error_lastname);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

    return false;

  }

  public function createTraveler($request, $container) {

    $error_firstname = null;
    $error_lastname = null;
    $error_general = null;

    $firstname = $request->getParam('firstname');
    $lastname = $request->getParam('lastname');

    $identifier = ( ( (int) substr($request->getParam('identifier'), 10) - 25879 ) / 5 );
    $trip = Trip::find($identifier);
    $user = $container->AuthHelper->getSessionUser();

    $shared = Shared::where('trip', $trip->id)->where('user', $user->id)->where('readonly', 'false')->get();
    if (count($shared) > 0) {
      $shared = true;
    } else {
      $shared = false;
    }

    if ($trip) {

      if ($trip->active == 'true') {

        if ($trip->owner == $user->id || $shared == true) {

          if (!empty($firstname) && !empty($lastname)) {

            if (strlen($firstname) <= 40 && strlen($lastname) <= 40) {

              $searchDuplicate = Traveler::where('trip', $trip->id)->where('firstname', $firstname)->where('lastname', $lastname)->get();
              if (count($searchDuplicate) == 0) {

                $traveler = Traveler::create([
                  'trip' => $trip->id,
                  'firstname' => $firstname,
                  'lastname' => $lastname
                ]);

                if ($traveler) {

                  $container->flash->addMessage('success', $container->translator->trans('trips.travelers.created'));
                  return true;

                } else {
                  $error_general = $container->translator->trans('auth.validation.error');
                }

              } else {
                $error_general = $container->translator->trans('trips.travelers.duplicate');
              }

            } else {

              if (strlen($firstname) > 40) {
                $error_firstname = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '40' ]);
              }
              if (strlen($lastname) > 40) {
                $error_lastname = $container->translator->trans('auth.validation.maxChar', [ '%number%' => '40' ]);
              }

            }

          } else {

            if (empty($firstname)) {
              $error_firstname = $container->translator->trans('auth.validation.required');
            }
            if (empty($lastname)) {
              $error_lastname = $container->translator->trans('auth.validation.required');
            }

          }

        } else {
          $error_general = $container->translator->trans('trips.error.auth');
        }

      } else {
        $error_general = $container->translator->trans('trips.error.notActive');
      }

    } else {
      $error_general = $container->translator->trans('trips.error.notFound');
    }

    if ($error_firstname) {
      $container->flash->addMessage('error_firstname', $error_firstname);
    }

    if ($error_lastname) {
      $container->flash->addMessage('error_lastname', $error_lastname);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

    return false;

  }

  public function getTraveler($traveler, $trip, $container) {

    $traveler_id = (int) $traveler / 55;
    $traveler_obj =  Traveler::where('id', $traveler_id)->where('trip', $trip)->first();

    if ($traveler_obj) {
      $traveler_obj->identifier = ( (int) $traveler_obj->id * 55 );
      return $traveler_obj;
    } else {
      return null;
    }

  }

  public function getTravelers($id, $container) {

    $travelers = Traveler::where('trip', $id)->orderBy('firstname', 'ASC')->get();

    foreach ($travelers as &$traveler) {

      $traveler->identifier = ( (int) $traveler->id * 55 );

    }

    return $travelers;

  }

  public function updateGeneral($request, $container) {

    $error_name = null;
    $error_period = null;
    $error_type = null;
    $error_phase = null;
    $error_currency = null;
    $error_general = null;

    $identifier = ( ( (int) substr($request->getParam('identifier'), 10) - 25879 ) / 5 );
    $trip = Trip::find($identifier);
    $user = $container->AuthHelper->getSessionUser();

    $shared = Shared::where('trip', $trip->id)->where('user', $user->id)->where('readonly', 'false')->get();
    if (count($shared) > 0) {
      $shared = true;
    } else {
      $shared = false;
    }

    if ($trip) {

      if ($trip->active == 'true') {

        if ($trip->owner == $user->id || $shared == true) {

          $name = $request->getParam('name');
          $period = $request->getParam('period');
          $type = $request->getParam('type');
          $phase = $request->getParam('phase');
          $currency = $request->getParam('currency');

          if (!empty($name) && !empty($period) && !empty($type) && !empty($phase) && !empty($currency)) {

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

                              if ($phase == '1' || $phase == '2' || $phase == '3' || $phase == '4' || $phase == '5') {

                                $currency_obj = $container->CurrencyHelper->getCurrency($currency);
                                if ($currency_obj) {

                                  $trip->name = $name;
                                  $trip->start = $start_split[2]."-".$start_split[1]."-".$start_split[0];
                                  $trip->stop = $stop_split[2]."-".$stop_split[1]."-".$stop_split[0];
                                  $trip->type = $type;
                                  $trip->phase = $phase;
                                  $trip->currency = $currency_obj->id;

                                  if ($trip->save()) {
                                    $container->flash->addMessage('success', $container->translator->trans('trips.settings.success'));
                                  } else {
                                    $error_general = $container->translator->trans('auth.validation.error');
                                  }

                                } else {
                                  $error_currency = $container->translator->trans('auth.validation.invalid');
                                }

                              } else {
                                $error_phase = $container->translator->trans('auth.validation.invalid');
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
            if (empty($phase)) {
              $error_phase = $container->translator->trans('auth.validation.required');
            }
            if (empty($currency)) {
              $error_currency = $container->translator->trans('auth.validation.required');
            }
          }

        } else {
          $error_general = $container->translator->trans('trips.error.auth');
        }

      } else {
        $error_general = $container->translator->trans('trips.error.notActive');
      }

    } else {
      $error_general = $container->translator->trans('trips.error.notFound');
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

    if ($error_phase) {
      $container->flash->addMessage('error_phase', $error_type);
    }

    if ($error_currency) {
      $container->flash->addMessage('error_currency', $error_currency);
    }

    if ($error_general) {
      $container->flash->addMessage('error', $error_general);
    }

  }

  public function getTrip($id, $container) {

    $identifier = ( ( (int) substr($id, 10) - 25879 ) / 5 );

    $trip = Trip::find($identifier);

    $sessionUser = $container->AuthHelper->getSessionUser();
    if ($sessionUser) {

      $trip->formatStartDate = date('d/m/Y', strtotime($trip->start));
      $trip->formatStopDate = date('d/m/Y', strtotime($trip->stop));
      $trip->formatPeriod = $trip->formatStartDate.' - '.$trip->formatStopDate;

      if ($trip->owner == $sessionUser->id) {

        // Add Identifier
        $trip->identifier = 'CTO-'.strtoupper(substr(md5($trip->owner), 0, 5)).'-'.(($trip->id*5)+25879);

        // Add Currency
        $trip->currencyCode = Currency::find($trip->currency)->code;

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
    $error_currency = null;

    $name = $request->getParam('name');
    $period = $request->getParam('period');
    $type = $request->getParam('type');
    $currency = $request->getParam('currency');

    if (!empty($name) && !empty($period) && !empty($type) && !empty($currency)) {

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

                        $currency_obj = $container->CurrencyHelper->getCurrency($currency);
                        if ($currency_obj) {

                          $user = $container->AuthHelper->getSessionUser();

                          $new_trip = Trip::create([
                                        'name' => $name,
                                        'start' => $start_split[2]."-".$start_split[1]."-".$start_split[0],
                                        'stop' => $stop_split[2]."-".$stop_split[1]."-".$stop_split[0],
                                        'active' => 'true',
                                        'owner' => $user->id,
                                        'type' => $type,
                                        'phase' => 1,
                                        'currency' => $currency_obj->id
                                      ]);

                          if ($new_trip) {
                            $container->flash->addMessage('success', $container->translator->trans('trips.create.success', [ '%name%' => $new_trip->name ]));
                            return true;
                          } else {
                            $error_general = $container->translator->trans('auth.validation.error');
                          }

                        } else {
                          $error_currency = $container->translator->trans('auth.validation.invalid');
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
      if (empty($currency)) {
        $error_currency = $container->translator->trans('auth.validation.required');
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

    if ($error_currency) {
      $container->flash->addMessage('error_currency', $error_currency);
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
