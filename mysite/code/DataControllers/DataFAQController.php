<?php

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\Versioning\Versioned;

class DataFAQController extends DataController {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $faqs = array();
    foreach (FAQ::get() as $faq) {
      $faqs[] = array(
        'question' => $faq->Question,
        'answer' => $faq->Answer
      );
    }

    $this->echoJson(array(
      'faqs' => $faqs
    ));
  }

}
