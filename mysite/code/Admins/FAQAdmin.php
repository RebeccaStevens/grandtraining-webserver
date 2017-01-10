<?php
class FAQAdmin extends ModelAdmin {

  private static $menu_title = 'Frequently Asked Questions';

  private static $url_segment = 'faq';

  private static $managed_models = array(
    'FAQ'
  );

  // private static $menu_icon = '';
}
