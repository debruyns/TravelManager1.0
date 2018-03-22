<?php

return [

  'auth' => [

    'signin' => [

      'pageTitle' => 'Inloggen',
      'header' => 'TravelManager Inloggen',
      'logoutMsg' => 'U bent succesvol uitgelogd',
      'cancelMsg' => 'U hebt het aanmelden geannuleerd',
      'forgot'  => 'Wachtwoord Vergeten'

    ],

    'verify' => [

      'pageTitle' => 'Two-Factor Authenticatie',
      'header' => 'Two-Factor Authenticatie'

    ],

    'recovery' => [

      'pageTitle' => 'Wachtwoord Vergeten',
      'header'  => 'Wachtwoord Vergeten',
      'successHeading' => 'Perfect!',
      'successMessage' => 'Binnen enkele momenten zullen we een email sturen naar %email% met de instructies om uw wachtwoord opnieuw in te stellen'

    ],

    'reset' => [

      'pageTitle' => 'Wachtwoord Instellen',
      'header' => 'Kies een nieuw wachtwoord',
      'invalid' => 'De opgegeven reset code is niet geldig of vervallen',
      'success' => 'U kan nu inloggen met uw nieuwe wachtwoord'

    ],

    'signup' => [

      'pageTitle' => 'Registreren',
      'header' => 'TravelManager Registreren',
      'successHeading' => 'Perfect!',
      'successMessage' => 'Binnen enkele momenten zullen we een email sturen naar %email% met de instructies om uw account te activeren'

    ],

    'activate' => [
      'success' => 'Uw account is klaar voor gebruik!'
    ],

    'validation' => [

      'required' => 'Dit veld is verplicht',
      'emailUnknown' => 'Er bestaat geen account met dit e-mailadres',
      'passwordIncorrect' => 'Het ingevoerde wachtwoord is niet correct',
      'notActive' => 'U moet eerst uw account activeren met behulp van de activatie e-mail',
      'userArchived' => 'Uw account is gearchiveerd<br />Contacteer <a href="mailto:support@citytakeoff.com">support@citytakeoff.com</a> om uw account te activeren',
      'userSuspended' => 'Uw account is door ons op inactief gezet<br />Contacteer <a href="mailto:support@citytakeoff.com">support@citytakeoff.com</a> om uw account te activeren',
      'verifyCode' => 'De ingevoerde authenticatie code is niet geldig',
      'recoveryTime' => 'U kan maar 1 aanvraag per 5 minuten uitvoeren',
      'wrongStatus' => 'Uw account is gearchiveerd of geschorst',
      'error' => 'Er is iets fout gegaan! Probeer het opnieuw',
      'minChar' => 'Dit veld moet minstens %number% karakters bevatten',
      'maxChar' => 'Dit veld mag maximaal %number% karakters bevatten',
      'match' => 'Dit veld komt niet overeen met uw wachtwoord',
      'invalidEmail' => 'Dit is geen geldig e-mailadres',
      'usedEmail' => 'Er bestaat al een account met dit e-mailadres',
      'invalidDate' => 'Dit is geen geldig datumbereik',
      'pastDate' => 'De begindatum van een trip mag niet in het verleden liggen',
      'pastStart' => 'De einddatum mag niet in het verleden van de begindatum liggen',
      'invalid' => 'Dit veld is niet geldig',
      'signInDisabled' => 'Vanwege een lopende update is het momenteel niet mogelijk om in te loggen',
      'signUpDisabled' => 'Vanwege een lopende update is het momenteel niet mogelijk om te registreren',
      'invalidInvite' => 'Registratie is momenteel enkel mogelijk voor een geselecteerde test groep'
    ]

  ]

];
