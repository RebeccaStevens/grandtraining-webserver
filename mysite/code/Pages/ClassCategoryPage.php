<?php
class ClassCategoryPage extends WebAppSubPage {
  private static $hide_ancestor = 'WebAppSubPage';
  private static $can_be_root = false;
  private static $allowed_children = array();

  public function canCreate() {
    return true;
  }

  private static $db = array(
    'Teaser' => 'HTMLVarchar(512)',
    'Description' => 'HTMLText'
  );

  private static $has_one = array(
    'CategoryImage' => 'Image'
  );

  private static $many_many = array(
    'HolidayClasses' => 'HolidayClass'
  );

  private static $summary_fields = array(
    'Name' => 'Name'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', UploadField::create('CategoryImage', 'Category Image'));

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Teaser', 'Teaser'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Description', 'Description'));
    $editorField->setRows(25);

    return $fields;
  }
}

class ClassCategoryPage_Controller extends WebAppSubPage_Controller {

}
