<?php

use SilverStripe\Admin\ModelAdmin;

class VenueAdmin extends ModelAdmin {

  private static $menu_title = 'Venues';

  private static $url_segment = 'venues';

  private static $managed_models = array(
    'Venue'
  );

  private static $menu_icon = 'mysite/cms-assets/images/menu-icons/venues.png';
}
