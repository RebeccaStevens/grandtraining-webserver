<?php

use SilverStripe\Admin\ModelAdmin;

class TestimonialAdmin extends ModelAdmin {

  private static $menu_title = 'Testimonials';

  private static $url_segment = 'testimonials';

  private static $managed_models = array(
    'Testimonial'
  );

  // private static $menu_icon = '';
}
