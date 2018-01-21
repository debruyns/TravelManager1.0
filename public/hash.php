<?php

  require '../app/Middleware/AuthenticatorMiddleware.php';

  echo password_hash("sddesign", PASSWORD_BCRYPT)."<br /><br />";

  $auth = new Authenticator();
  $secret = $auth->generateRandomSecret();

  echo $secret;

  echo "<br /><br />";
  $QR = $auth->getQR('sam@citytakeoff.com', $secret, "TravelManager");

  echo "<img src='{$QR}' />"


?>
