<?php

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Core\Extension;

class ContactAdmin extends ModelAdmin {

  private static $menu_title = 'Messages Received';

  private static $url_segment = 'messages';

  private static $managed_models = array(
    'ContactMessage'
  );

  private static $menu_icon = 'mysite/cms-images/menu-icons/messages.png';
}

class ContactAdminExtension extends Extension {
  function updateEditForm(&$form) {
    $c = $form->fields->dataFieldByName('ContactMessage');
    $c->config->removeComponent($c->config->getComponentByType('SilverStripe\Forms\GridField\GridFieldAddNewButton'));
  }
}
