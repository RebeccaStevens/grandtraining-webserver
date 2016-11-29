<?php
class Page extends SiteTree {

  public function canCreate($member = null) {
    return true;
  }
}
class Page_Controller extends ContentController {

}
