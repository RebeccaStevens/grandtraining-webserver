<?php
class BookingsPage extends WebAppPage {
  public $WebAppPageName = 'bookings';
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

class BookingsPage_Controller extends WebAppPage_Controller {

}
