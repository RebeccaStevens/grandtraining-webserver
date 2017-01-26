<?php

use SilverStripe\CMS\Model\SiteTree;

class Page extends SiteTree {

  public function canCreate($member = null, $context = array()) {
    return true;
  }
}
