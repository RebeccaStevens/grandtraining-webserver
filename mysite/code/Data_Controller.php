<?php

/**
 * This contoller manages data request for the site.
 */
class Data_Controller extends Controller {

  private static $url_handlers = array(
    'config' => 'siteConfigData',
    'home' => 'homePageData'
  );

  private static $allowed_actions = array(
    'siteConfigData',
    'homePageData'
  );

  /**
   * Handles request for config.json
   */
  public function siteConfigData(SS_HTTPRequest $request) {
    if (!$this::ensureJsonRequest($request)) {
      return;
    }
    $this->echoJson();
  }

  /**
   * Handles request for home.json
   */
  public function homePageData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }
    $this->echoJson();
  }

  /**
   * Echo out the given data as json and set the response Content-Type header to json.
   *
   * @param mixed $data The data to encode as json
   */
  private function echoJson($data) {
    $this->response->addHeader('Content-Type', 'application/json');
    echo json_encode($data);
  }

  /**
   * Ensure the request is for a json file.
   * Throws a 404 if the request is not for a json file.
   *
   * @return boolean Whether or not the request was for a json file.
   */
  private function ensureJsonRequest(SS_HTTPRequest $request) {
    if ($request->getExtension() === 'json') {
      return true;
    }
    $this->httpError(404);
    return false;
  }
}