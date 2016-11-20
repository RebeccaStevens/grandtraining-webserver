<?php
class AccountPage extends WebAppPage {
  public $WebAppPageName = 'account';
  private static $hide_ancestor = 'WebAppPage';
  private static $can_be_root = true;
  private static $allowed_children = array ();

  /**
   * Only allow one instance of this page type.
   */
  public function canCreate() {
    return DataObject::get(__CLASS__)->count() === 0;
  }
}

class AccountPage_Controller extends WebAppPage_Controller {

}
