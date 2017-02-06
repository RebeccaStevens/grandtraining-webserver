<?php

use SilverStripe\Admin\ModelAdmin;

class ContactAdmin extends ModelAdmin {

  private static $menu_title = 'Messages Received';

  private static $url_segment = 'messages';

  private static $managed_models = array(
    'ContactMessage'
  );

  private static $menu_icon = 'mysite/cms-assets/images/menu-icons/messages.png';

  public $showImportForm = false;

  public function getEditForm($id = null, $fields = null) {
    $form = parent::getEditForm($id, $fields);

    $c = $form->fields->dataFieldByName('ContactMessage');
    $c->config->removeComponent($c->config->getComponentByType('SilverStripe\Forms\GridField\GridFieldAddNewButton'));
    return $form;
  }
}
