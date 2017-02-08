<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Control\Controller;

class WebAppPage extends SiteTree {

  public $WebAppPageName = null;

  /**
   * This is an abstract class.
   * It cannot be craeted.
   */
  public function canCreate($member = null, $context = array()) {
    return false;
  }

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', ReadonlyField::create(null, 'Web App Page Name', $this->WebAppPageName), 'Title');

    $urlSegment = $fields->fieldByName('Root.Main.URLSegment');
    $urlSegment->setURLPrefix(SITE_APP_URL);

    $fields->removeFieldFromTab('Root.Main', 'Content');
    $fields->removeFieldFromTab('Root.Main', 'Metadata');

    return $fields;
  }

  public function AbsoluteLink($action = null) {
    return Controller::join_links(SITE_APP_URL, $this->RelativeLink($action));
  }

  public function PreviewLink($action = null) {
    return $this->AbsoluteLink($action);
  }
}
