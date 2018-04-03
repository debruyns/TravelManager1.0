<?php

return [

  'trips' => [

    'general' => [

      'pageTitle' => 'Trips',
      'mytrips' => 'Door mij aangemaakte trips',
      'sharedtrips' => 'Trips aangemaakt door anderen',
      'status' => 'Status',
      'phase' => 'Fase',
      'shared' => 'Gedeeld',
      'type' => 'Type',
      'manage' => 'Beheren',
      'view' => 'Bekijken',
      'nofound' => 'Je hebt nog geen trips',
      'noshared' => 'Er zijn momenteel geen trips met je gedeeld',
      'owner' => 'Eigenaar',
      'create' => 'Nieuwe trip aanmaken',
      'currency' => 'Valuta'

    ],

    'status' => [

      'active' => 'Actief',
      'archived' => 'Gearchiveerd'

    ],

    'phase' => [

      '1' => 'Plannen',
      '2' => 'Gepland',
      '3' => 'Gestart',
      '4' => 'Beëindigd',
      '5' => "Geannuleerd"

    ],

    'shared' => [

      'yes' => 'Ja',
      'no' => 'Nee'

    ],

    'type' => [

      'leisure' => 'Vrije Tijd',
      'business' => 'Zaken'

    ],

    'create' => [

      'pageTitle' => 'Nieuwe Trip',
      'title' => 'Nieuwe trip aanmaken',
      'name' => 'Trip Naam',
      'namePlace' => 'Vul een naam in van maximum 20 karakters',
      'period' => 'Trip Periode',
      'datePlace' => 'dd/mm/jjjj',
      'rangePlace' => 'dd/mm/jjjj - dd/mm/jjjj',
      'success' => 'Uw %name% trip is succesvol aangemaakt'

    ],

    'manage' => [

      'notFound' => 'De gevraagde trip kan niet worden weergegeven'

    ],

    'menu' => [

      'general' => 'Algemeen',
      'travelers' => 'Reizigers',
      'accommodation' => 'Accommodaties',
      'transport' => 'Vervoer',
      'budget' => 'Budget',
      'planning' => 'Planning'

    ],

    'settings' => [

      'header' => 'Algemene Informatie',
      'success' => 'De wijzigingen zijn succesvol opgeslagen'

    ],


    'travelers' => [

      'header' => 'Reizigers',
      'nofound' => 'Er bestaan nog geen reizigers voor deze trip',
      'firstname' => 'Voornaam',
      'lastname' => 'Achternaam',
      'details' => 'Details',
      'edit' => 'Wijzigen',
      'delete' => 'Verwijderen',
      'create' => 'Nieuwe reiziger aanmaken',
      'firstnamePlace' => 'Vul een voornaam in van maximum 40 karakters',
      'lastnamePlace' => 'Vul een achternaam in van maximum 40 karakters',
      'duplicate' => 'Er bestaat al een reiziger met exact dezelfde naam tijdens deze trip (Voeg iets extra toe om de namen van de reizigers uniek te houden)',
      'created' => 'De nieuwe reiziger is aangemaakt',
      'change' => 'Reiziger wijzigen',
      'changed' => 'De reiziger is succesvol gewijzigd',
      'deleted' => 'De reiziger is succesvol verwijderd',
      'remove' => 'Reiziger verwijderen',
      'confirmDelete' => 'Bent u zeker dat u deze reiziger wil verwijderen?',
      'detailsTitle' => 'Reiziger Details'

    ],

    'accommodations' => [

      'header' => 'Accommodaties',
      'nofound' => 'Er bestaan nog geen accommodaties voor deze trip',
      'name' => 'Naam',
      'checkin' => 'Check-In',
      'checkout' => 'Check-Uit',
      'reference' => 'Referentie',
      'price' => 'Prijs',
      'details' => 'Details',
      'edit' => 'Wijzigen',
      'delete' => 'Verwijderen',
      'create' => 'Nieuwe accommodatie aanmaken',
      'dates' => 'Check-In/Uit',
      'detailsTitle' => 'Accommodatie Details',
      'namePlace' => 'Accommodatienaam',
      'periodPlace' => 'dd/mm/jjjj - dd/mm/jjjj',
      'referencePlace' => 'Boeking referentie (optioneel)',
      'pricePlace' => 'Prijs van de boeking',
      'meals' => 'Maaltijden',
      'cancelable' => 'Gratis annuleerbaar',
      'cancelbefore' => 'Gratis annuleren voor',
      'cancelbeforePlace' => 'dd/mm/jjjj',

      'mealOptions' => [

        '1' => 'Geen maaltijden inbegrepen',
        '2' => 'Ontbijt',
        '3' => 'Halfpension',
        '4' => 'Volpension',
        '5' => 'All inclusive'

      ],

      'cancelOptions' => [

        'true' => 'Ja',
        'false' => 'Nee'

      ]

    ],

    'error' => [

      'notFound' => 'Trip bestaat niet',
      'auth' => 'U bent niet gemachtigd om deze trip te wijzigen',
      'notActive' => 'U kunt alleen actieve trips wijzigen'

    ]

  ]

];
