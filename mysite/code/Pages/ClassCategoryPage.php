<?php
class ClassCategoryPage extends WebAppSubPage {
  private static $hide_ancestor = 'WebAppSubPage';
  private static $can_be_root = false;
  private static $allowed_children = array();

  public function canCreate($member = null) {
    return true;
  }

  private static $db = array(
    'Teaser' => 'HTMLText',
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
    $editorField->setDescription('Will be shown on the class hub page. If left blank, the first paragraph of the description will be used.');

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Description', 'Description'));
    $editorField->setRows(25);
    $editorField->setDescription('Will be shown on the class details page.');

    return $fields;
  }
}

class ClassCategoryPage_Controller extends WebAppSubPage_Controller {

}
