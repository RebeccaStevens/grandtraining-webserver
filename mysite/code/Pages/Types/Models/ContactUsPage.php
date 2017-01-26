<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class ContactUsPage extends WebAppPage {

  public $WebAppPageName = 'contact-us';
  private static $hide_ancestor = 'WebAppPage';
  private static $can_be_root = true;
  private static $allowed_children = array();

  private static $db = array(
    'ConfirmationMessage' => 'HTMLVarchar(512)',
    'ErrorMessage' => 'HTMLVarchar(512)'
  );

  /**
   * Only allow one instance of this page type.
   */
  public function canCreate($member = null, $context = array()) {
    return DataObject::get(__CLASS__)->count() === 0;
  }

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.SendMessage', $editorField = HTMLEditorField::create('ConfirmationMessage', 'Send Confirmation Message'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.SendMessage', $editorField = HTMLEditorField::create('ErrorMessage', 'Send Error Message'));
    $editorField->setRows(10);

    return $fields;
  }
}
