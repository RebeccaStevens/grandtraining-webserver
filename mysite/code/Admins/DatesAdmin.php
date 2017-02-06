<?php

use SilverStripe\Admin\ModelAdmin;

class DatesAdmin extends ModelAdmin {

  private static $menu_title = 'Bookings Dates';

  private static $url_segment = 'dates';

  private static $managed_models = array(
    'BookingDate'
  );

  private static $menu_icon = 'mysite/cms-assets/images/menu-icons/calendar.png';
}
