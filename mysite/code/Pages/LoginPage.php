<?php
class LoginPage extends WebAppPage {
  public $WebAppPageName = 'signin';
  private static $can_be_root = true;
  private static $allowed_children = array ();

  /**
   * Only allow one instance of this page type.
   */
  public function canCreate() {
    return DataObject::get(__CLASS__)->count() === 0;
  }
}

class LoginPage_Controller extends WebAppPage_Controller {

}
