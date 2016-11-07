<?php
class ClassesAdmin extends ModelAdmin {

  private static $menu_title = 'Classes';

  private static $url_segment = 'classes';

  private static $managed_models = array(
    'ClassCategory',
    'HolidayClass'
  );

  // private static $menu_icon = '';
}
