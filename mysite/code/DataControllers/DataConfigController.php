<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\Control\Controller;
use SilverStripe\Security\Member;

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

    $data = array(
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
        'contact-us' => ltrim(Controller::join_links(DataObject::get(ContactUsPage::class)[0]->Link(), 'form-handler'), '/'),
        'signin'=> ltrim(Controller::join_links(DataObject::get(LoginPage::class)[0]->Link(), 'login-handler'), '/'),
        'signup'=> ltrim(Controller::join_links(DataObject::get(LoginPage::class)[0]->Link(), 'signup-handler'), '/')
      ),
      'etrain' => array(
        'title' => $siteConfig->EtrainTitle,
        'menuTitle' => $siteConfig->EtrainMenuTitle,
        'url' => $siteConfig->EtrainLink
      )
    );

    if (Member::currentUser() !== null) {
      $data['user'] = LoginPageController::getUserData();
    }

    $this->echoJson($data);
  }

}
