<?php
class DataConfig_Controller extends Data_Controller {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(SS_HTTPRequest $request) {
    if (!$this::ensureJsonRequest($request)) {
      return;
    }

    $siteConfig = SiteConfig::current_site_config();

    $company = array(
      'name' => $siteConfig->Title,
      'slogan' => $siteConfig->Tagline,
      'logo' => $this->getImageData($siteConfig->Logo())
    );

    $pages = array();
    foreach (WebAppPage::get() as $page) {
      if ($page->WebAppPageName === null) {
        continue;
      }
      $pages[$page->WebAppPageName] = array(
        'tilte' => $page->Title,
        'menuTitle' => $page->MenuTitle,
        'url' => $page->Link()
      );
    }

    $venues = array();
    foreach (Venue::get() as $venue) {
      if (!array_key_exists($venue->City, $venues)) {
        $venues[$venue->City] = array();
      }
      $venues[$venue->City][$venue->ClientFormattedID()] = array(
        'id' => $venue->ClientFormattedID(),
        'city' => $venue->City,
        'name' => $venue->Name,
        'fullname' => $venue->FullName,
      );
    }

    $this->echoJson(array(
      'company' => $company,
      'pages' => $pages,
      'venues' => $venues,
      'api-configs' => array(
        'map-config' => array(
          'api-key' => $siteConfig->GoogleMapsApiKey,
          'zoom' => $siteConfig->GoogleMapsZoom
        ),
        'recaptcha-config' => array(
          'api-key' => $siteConfig->RecaptchaApiKey,
        )
      ),
      'links' => array(
        'bookclass' => "/bookings/booknow/bookclass"
      ),
      'countryCode' => "+64"
    ));
  }

}
