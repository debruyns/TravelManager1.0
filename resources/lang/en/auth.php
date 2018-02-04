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
      'success' => 'You can now login with your new password'

    ],

    'signup' => [

      'pageTitle' => 'Sign Up',
      'header' => 'TravelManager Sign Up',
      'successHeading' => 'Great!',
      'successMessage' => 'In a few moments we will send an email to %email% with the instruction to activate your account'

    ],

    'activate' => [
      'success' => 'Your account is ready!'
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
      'error' => 'Something went wrong! Please try again',
      'minChar' => 'This field must contain at least %number% characters',
      'maxChar' => 'This field can contain a maximum of %number% characters',
      'match' => 'This field does not match your password',
      'invalidEmail' => 'This is not a valid email address',
      'usedEmail' => 'An account already exists with this email address',
      'invalidDate' => 'This is not a valid date',
      'pastDate' => 'The start date of a trip may not be in the past',
      'pastStart' => 'The end date can not be in the past of the start date',
      'invalid' => 'This field is not valid'

    ]

  ]

];
