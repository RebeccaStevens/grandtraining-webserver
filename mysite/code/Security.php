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

$newSession = false;

// if the app is making a request, load the user's session using the given id-token.
// Note: id-token in away also works as a CSRF token as it must explicitly be set with each request.
if ($requestIsFromApp) {
  $DATA = array();
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $DATA = $_GET;
  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DATA = $_POST;
  }

  $newSession = isset($DATA['create-new-id-token']) ? !!$DATA['create-new-id-token'] : false;
  $token = !$newSession && isset($DATA['id-token']) ? $DATA['id-token'] : null;

  // only start a session if a new session was requested or a token given
  if ($newSession || $token !== null) {
    if (!$newSession) {
      // if the token is in a invalid format, treat this as a new session
      $newSession = preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $token) !== 1;
    }
    if (!$newSession) {
      session_id($token);
    }
  }
} else {
  $newSession = !isset($_COOKIE['PHPSESSID']);
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
