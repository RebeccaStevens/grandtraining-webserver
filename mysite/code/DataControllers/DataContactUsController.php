<?php

use SilverStripe\Control\HTTPRequest;

class DataContactUsController extends DataController {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $contactUsPage = ContactUsPage::get()[0];

    $locations = array();
    foreach (Venue::get()->sort('Region')->column('Region') as $region) {
      $locations[] = array(
        'fullname' => $region
      );
    }

    $locations[] = array(
      'fullname' => 'Elsewhere'
    );

    $this->echoJson(array(
      'locations' => $locations,
      'confirmationMessage' => $contactUsPage->ConfirmationMessage,
      'errorMessage' => $contactUsPage->ErrorMessage
    ));
  }

}
