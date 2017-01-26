<?php

use SilverStripe\Admin\ModelAdmin;

class FAQAdmin extends ModelAdmin {

  private static $menu_title = 'Frequently Asked Questions';

  private static $url_segment = 'faq';

  private static $managed_models = array(
    'FAQ'
  );

  // private static $menu_icon = '';

  public function getEditForm($id = null, $fields = null) {
    $form = parent::getEditForm($id, $fields);

    // $gridFieldName is generated from the ModelClass, eg if the Class 'Product'
    // is managed by this ModelAdmin, the GridField for it will also be named 'Product'

    $gridFieldName = $this->sanitiseClassName($this->modelClass);
    $gridField = $form->Fields()->fieldByName($gridFieldName);

    // modify the list view.
    if ($gridField) {
      $gridField->getConfig()->addComponent(new GridFieldOrderableRows('SortOrder'));
    }

    return $form;
  }
}
