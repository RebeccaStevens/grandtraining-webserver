<?php
class DataAboutUs_Controller extends Data_Controller {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $venues = array();
    foreach (Venue::get() as $venue) {
      $cfid = $venue->ClientFormattedID();
      $venues[$cfid] = array(
        'id' => $cfid,
        'region' => $venue->Region,
        'name' => $venue->Name,
        'address' => $venue->Address,
        'contactNumber' => $venue->ContactNumber,
        'email' => $venue->EmailAddress,
        'notes' => $venue->Notes,
        'location' => array(
          'latitude' => floatval($venue->Latitude),
          'longitude' => floatval($venue->Longitude)
        )
      );
    }

    $this->echoJson(array(
      'venues' => $venues
    ));
  }

}
