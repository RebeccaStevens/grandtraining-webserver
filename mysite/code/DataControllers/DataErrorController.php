<?php

use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\CMS\Model\ErrorPage;

class DataErrorController extends DataController {

  private static $url_handlers = array(
    '$Code' => 'getErrorData'
  );
  private static $allowed_actions = array(
    'getErrorData'
  );

  /**
   * Handles request for error data
   */
  public function getErrorData(HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $statusCode = $this->getRequest()->param('Code');

    $page = ErrorPage::get()->filter(array('ErrorCode' => $statusCode))->first();

    if ($page === null) {
      $this->httpError(404);
      return;
    }

    $this->echoJson(array(
      'title' => $page->Title,
      'menuTitle' => $page->MenuTitle,
      'message' => $page->Content
    ));
  }

}
