<?php

return [

  'trips' => [

    'general' => [

      'pageTitle' => 'Trips',
      'mytrips' => 'Trips created by me',
      'sharedtrips' => 'Trips created by others',
      'status' => 'Status',
      'phase' => 'Phase',
      'shared' => 'Shared',
      'type' => 'Type',
      'manage' => 'Manage',
      'view' => 'View',
      'nofound' => 'You do not have any trips yet',
      'noshared' => 'No trips are currently shared with you',
      'owner' => 'Owner',
      'create' => 'Create a new trip',
      'currency' => 'Currency'

    ],

    'status' => [

      'active' => 'Active',
      'archived' => 'Archived'

    ],

    'phase' => [

      '1' => 'Planning',
      '2' => 'Planned',
      '3' => 'Started',
      '4' => 'Finished',
      '5' => "Canceled"

    ],

    'shared' => [

      'yes' => 'Yes',
      'no' => 'No'

    ],

    'type' => [

      'leisure' => 'Leisure',
      'business' => 'Business'

    ],

    'create' => [

      'pageTitle' => 'New Trip',
      'title' => 'Create a new trip',
      'name' => 'Trip Name',
      'namePlace' => 'Fill in a name of maximum 20 characters',
      'period' => 'Trip Period',
      'datePlace' => 'dd/mm/yyyy',
      'rangePlace' => 'dd/mm/yyyy - dd/mm/yyyy',
      'success' => 'Your %name% trip has been successfully created'

    ],

    'manage' => [

      'notFound' => 'The requested trip can not be displayed'

    ],

    'menu' => [

      'general' => 'General',
      'travelers' => 'Travelers',
      'accommodation' => 'Accommodations',
      'transport' => 'Transport',
      'budget' => 'Budget',
      'planning' => 'Planning'

    ],

    'settings' => [

      'header' => 'General Information',
      'success' => 'The changes were saved successfully'

    ],

    'travelers' => [

      'header' => 'Travelers',
      'nofound' => 'There are no travelers for this trip yet',
      'firstname' => 'Firstname',
      'lastname' => 'Lastname',
      'details' => 'Details',
      'edit' => 'Edit',
      'delete' => 'Delete',
      'create' => 'Create new traveler',
      'firstnamePlace' => 'Enter a firstname of up to 40 characters',
      'lastnamePlace' => 'Enter a lastname of up to 40 characters',
      'duplicate' => 'There is already a traveler with exactly the same name during this trip (Add something extra to keep the names of the travelers unique)',
      'created' => 'The new traveler has been created',
      'change' => 'Edit traveler',
      'changed' => 'The traveler has been successfully changed',
      'deleted' => 'The traveler has been successfully deleted',
      'remove' => 'Delete traveler',
      'confirmDelete' => 'Are you sure you want to delete this traveler?',
      'detailsTitle' => 'Traveler Details'

    ],

    'accommodations' => [

      'header' => 'Accommodations',
      'nofound' => 'There are no accommodations for this trip yet',
      'name' => 'Name',
      'checkin' => 'Check-In',
      'checkout' => 'Check-Out',
      'reference' => 'Reference',
      'price' => 'Price',
      'details' => 'Details',
      'edit' => 'Edit',
      'delete' => 'Delete',
      'create' => 'Create new accommodation',
      'dates' => 'Check-In/Out',
      'detailsTitle' => 'Accommodation Details',
      'namePlace' => 'Accommodation name',
      'periodPlace' => 'dd/mm/yyyy - dd/mm/yyyy',
      'referencePlace' => 'Booking reference (optional)',
      'pricePlace' => 'Booking price',
      'meals' => 'Meals',
      'cancelable' => 'Cancelable for free',
      'cancelbefore' => 'Cancelable for free before',
      'cancelbeforePlace' => 'dd/mm/yyyy',

      'mealOptions' => [

        '1' => 'No meals included',
        '2' => 'Breakfast',
        '3' => 'Half board',
        '4' => 'Full board',
        '5' => 'All inclusive'

      ],

      'cancelOptions' => [

        'true' => 'Yes',
        'false' => 'No'

      ]

    ],

    'error' => [

      'notFound' => 'Trip does not exist',
      'auth' => 'You do not have permission to change this trip',
      'notActive' => 'You can only change trips that are active'

    ]

  ]

];
