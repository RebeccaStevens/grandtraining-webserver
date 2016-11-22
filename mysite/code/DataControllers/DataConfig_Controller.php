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
      'logo' => array(
        'src' => $siteConfig->Logo()->URL,
        'placeholder' => $this->getPlaceholderImage($siteConfig->Logo())
      )
    );

    $pages = array();
    foreach (WebAppPage::get() as $key => $page) {
      $pages[$page->WebAppPageName] = array(
        'tilte' => $page->Title,
        'menuTitle' => $page->MenuTitle,
        'url' => $page->Link()
      );
    }

    $cities = array();
    foreach (Venue::get() as $key => $venue) {
      if (!array_key_exists($venue->City, $cities)) {
        $cities[$venue->City] = array();
      }
      $cities[$venue->City][$venue->ClientFormattedID()] = array(
        'id' => $venue->ClientFormattedID(),
        'city' => $venue->City,
        'name' => $venue->Name,
        'fullname' => $venue->FullName,
      );
    }

    $this->echoJson(array(
      'company' => $company,
      'pages' => $pages,
      'cities' => $cities,
      'links' => array(
        'bookclass' => "/bookings/booknow/bookclass"
      ),
      'countryCode' => "+64"
    ));
  }

}
