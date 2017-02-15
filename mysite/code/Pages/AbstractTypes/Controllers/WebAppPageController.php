<?php

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\SiteConfig\SiteConfig;

class WebAppPageController extends ContentController {

  /**
   * The page isn't on this site so it can't be initialised
   */
  public function init() {
    parent::init();
    // if not a request from the app
    if (!REQUEST_IS_FROM_APP) {
      $this->httpError(404);
    }
  }

  /**
   * Checks if the Recaptcha was successful.
   *
   * @param {String} - The response from the client
   * @return {Array} - The Recaptcha response
   */
  protected function checkRecaptcha($response) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
      'secret' => SiteConfig::current_site_config()->RecaptchaApiSecretKey,
      'response' => $response,
      'remoteip' => $_SERVER['REMOTE_ADDR']);

    $options = array(
      'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
      )
    );

    $context  = stream_context_create($options);
    return json_decode(file_get_contents($url, false, $context), true);
  }
}
