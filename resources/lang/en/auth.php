<?php

return [

  'auth' => [

    'signin' => [

      'pageTitle' => 'Sign In',
      'header' => 'TravelManager Sign In',
      'logoutMsg' => 'You have successfully logged out',
      'cancelMsg' => 'You have canceled the sign in',
      'forgot' => 'Forgot Password'

    ],

    'verify' => [

      'pageTitle' => 'Two-Factor Authentication',
      'header' => 'Two-Factor Authentication'

    ],

    'recovery' => [

      'pageTitle' => 'Forgot Password',
      'header'  => 'Forgot Password',
      'successHeading' => 'Great!',
      'successMessage' => 'In a few moments we will send an email to %email% with the instruction to reset your password'

    ],

    'reset' => [

      'pageTitle' => 'Reset Password',
      'header' => 'Choose a new password',
      'invalid' => 'The provided reset code is invalid/expired',

    ],

    'validation' => [

      'required' => 'This field is required',
      'emailUnknown' => 'There is no account with this email address',
      'passwordIncorrect' => 'The entered password is incorrect',
      'notActive' => 'You must first activate your account using the activation email',
      'userArchived' => 'Your account has been archived<br />Contact <a href="mailto:support@citytakeoff.com">support@citytakeoff.com</a> to activate your account',
      'userSuspended' => 'Your account has been suspended<br />Contact <a href="mailto:support@citytakeoff.com">support@citytakeoff.com</a> to activate your account',
      'verifyCode' => 'The entered authentication code is not valid',
      'recoveryTime' => 'You can only do one application per 5 minutes',
      'wrongStatus' => 'Your account has been archived or suspended',
      'error' => 'Something went wrong! Please try again'

    ]

  ]

];
