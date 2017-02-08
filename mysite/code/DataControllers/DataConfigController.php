<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\SiteConfig\SiteConfig;

class DataConfigController extends DataController {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(HTTPRequest $request) {
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
      $venueCFID = $venue->ClientFormattedID();
      $regions[$venue->Region][$venueCFID] = array(
        'id' => $venueCFID,
        'region' => $venue->Region,
        'name' => $venue->Name,
        'fullname' => $venue->FullName,
      );
    }

    $this->echoJson(array(
      'id-token' => session_id(),
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
        'bookclass' => '/bookings/booknow/bookclass',
        'social' => array(
          'facebook' => $siteConfig->FacebookLink,
          'google-plus' => $siteConfig->GooglePlusLink,
          'twitter' => $siteConfig->TwitterLink,
          'youtube' => $siteConfig->YouTubeLink
        )
      ),
      'form-handlers' => array(
        'contact-us' => DataObject::get(ContactUsPage::class)[0]->URLSegment . '/form-handler'
      ),
      'etrain' => array(
        'title' => $siteConfig->EtrainTitle,
        'menuTitle' => $siteConfig->EtrainMenuTitle,
        'url' => $siteConfig->EtrainLink
      )
    ));
  }

}
