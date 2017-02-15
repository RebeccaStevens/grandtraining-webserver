<?php

use SilverStripe\Control\Director;
use SilverStripe\Control\Session;

// figure out if the request came from the app.
// Note: HTTP_REFERER forgery shouldn't be an issue here.
if (isset($_SERVER['HTTP_REFERER'])) {
  if (parse_url($_SERVER['HTTP_REFERER'])['host'] === parse_url(SITE_APP_URL)['host']) {
    define('REQUEST_IS_FROM_APP', true);
  }
}
if (!defined('REQUEST_IS_FROM_APP')) {
  define('REQUEST_IS_FROM_APP', false);
}

// Allow the app to use request made to the api
if (Director::isDev()) {
  header('Access-Control-Allow-Origin: *');
} else {
  header('Access-Control-Allow-Origin: ' . rtrim(SITE_APP_URL, '/'));
}

$newSession = false;
$sid = null;

// if the app is making a request, load the user's session using the given id-token.
// Note: id-token in away also works as a CSRF token as it must explicitly be set with each request.
if (REQUEST_IS_FROM_APP) {
  $DATA = array();
  if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $DATA = $_GET;
  } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $DATA = $_POST;
  }

  $newSession = isset($DATA['create-new-id-token']) ? !!$DATA['create-new-id-token'] : false;
  $token = !$newSession && isset($DATA['id-token']) ? $DATA['id-token'] : null;
  define('ID_TOKEN_GIVEN', $token);

  // only start a session if a new session was requested or a token given
  if ($newSession || $token !== null) {
    if (!$newSession) {
      // if the token is in a invalid format, treat this as a new session
      $newSession = preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $token) !== 1;
    }
    if (!$newSession) {
      $sid = $token;
    }
  }
} else {
  $newSession = !isset($_COOKIE['PHPSESSID']);
}

if (!defined('ID_TOKEN_GIVEN')) {
  define('ID_TOKEN_GIVEN', null);
}

Session::start($sid);

// if the expiry date is not set or is expired
$expiresAt = Session::get('expiresAt');
if ($expiresAt === null || $expiresAt < date_create()) {
  if (!$newSession) {
    // make a new session for this user
    session_regenerate_id(true);
    Session::clear_all();
  }
  // set the new expiry date
  Session::set('expiresAt', date_create()->add(new DateInterval('PT12H'))); // 12 hours from now
}
