<?php
class DataContactUs_Controller extends Data_Controller {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $contactUsPage = ContactUsPage::get()[0];

    $locations = array();
    foreach (Venue::get() as $venue) {
      $locations[] = array(
        'fullname' => $venue->FullName
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
