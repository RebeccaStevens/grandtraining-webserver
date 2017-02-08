<?php

use SilverStripe\Control\Director;

// figure out if the request came from the app.
// Note: HTTP_REFERER forgery shouldn't be an issue here.
$requestIsFromApp = false;

if (isset($_SERVER['HTTP_REFERER'])) {
  if (parse_url($_SERVER['HTTP_REFERER'])['host'] === parse_url(SITE_APP_URL)['host']) {
    $requestIsFromApp = true;
  }
}

// Allow the app to use request made to the api
if (Director::isDev()) {
  header('Access-Control-Allow-Origin: *');
} else {
  header('Access-Control-Allow-Origin: ' . rtrim(SITE_APP_URL, '/'));
}


// if the app is making a request, load the user's session using the given id-token.
// Note: id-token in away also works as a CSRF token as it must explicitly be set with each request.
if ($requestIsFromApp) {
  $token = null;  // only allow getting the id token from GET or POST methods
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $token = $_GET['id-token'];
  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['id-token'];
  }

  // if the token is set and is valid then this is not a new session
  $newSession = !(isset($token) && preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $token) > 0);

  if (!$newSession) {
    session_id($token);
  }
  session_start();

  // if the expiry date is not set or is expired
  if (!isset($_SESSION['expiresAt']) || $_SESSION['expiresAt'] < date_create()) {
    if (!$newSession) {
      // make a new session for this user
      session_regenerate_id(true);
      $_SESSION = array();
    }
    // set the new expiry date
    $_SESSION['expiresAt'] = date_create()->add(new DateInterval('PT12H')); // 12 hours from now
  }
}
