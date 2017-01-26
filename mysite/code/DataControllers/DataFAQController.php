<?php

use SilverStripe\Control\HTTPRequest;

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

    $faqPage = FAQPage::get()[0];

    $faqs = array();
    foreach ($faqPage->FAQs() as $faq) {
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
