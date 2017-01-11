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
        'title' => $page->Title,
        'menuTitle' => $page->MenuTitle,
        'url' => $page->Link()
      );
    }

    $regions = array();
    foreach (Venue::get()->sort(array('Region' => 'ASC', 'Name' => 'ASC')) as $venue) {
      if (!array_key_exists($venue->Region, $regions)) {
        $regions[$venue->Region] = array();
      }
      $regions[$venue->Region][$venue->ClientFormattedID()] = array(
        'id' => $venue->ClientFormattedID(),
        'region' => $venue->Region,
        'name' => $venue->Name,
        'fullname' => $venue->FullName,
      );
    }

    $this->echoJson(array(
      'company' => $company,
      'pages' => $pages,
      'regions' => $regions,
      'api-configs' => array(
        'map-config' => array(
          'api-key' => $siteConfig->GoogleMapsApiKey,
          'zoom' => intval($siteConfig->GoogleMapsZoom)
        ),
        'recaptcha-config' => array(
          'site-key' => $siteConfig->RecaptchaApiSiteKey,
        )
      ),
      'links' => array(
        'bookclass' => "/bookings/booknow/bookclass"
      ),
      'etrain' => array(
        'title' => $siteConfig->EtrainTitle,
        'menuTitle' => $siteConfig->EtrainMenuTitle,
        'url' => $siteConfig->EtrainLink
      )
    ));
  }

}
