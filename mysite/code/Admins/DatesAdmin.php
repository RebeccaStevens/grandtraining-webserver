<?php

use SilverStripe\Admin\ModelAdmin;

class DatesAdmin extends ModelAdmin {

  private static $menu_title = 'Bookings Dates';

  private static $url_segment = 'dates';

  private static $managed_models = array(
    'BookingDateRange'
  );

  // private static $menu_icon = '';
}
