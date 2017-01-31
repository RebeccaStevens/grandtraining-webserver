<?php

use SilverStripe\Forms\UploadField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class ClassCategoryPage extends WebAppSubPage {

  private static $hide_ancestor = 'WebAppSubPage';
  private static $can_be_root = false;
  private static $allowed_children = array();

  public function canCreate($member = null, $context = array()) {
    return true;
  }

  private static $db = array(
    'Teaser' => 'HTMLText',
    'Description' => 'HTMLText'
  );

  private static $has_one = array(
    'CategoryImage' => 'SilverStripe\Assets\Image'
  );

  private static $many_many = array(
    'HolidayClasses' => 'HolidayClass'
  );

  private static $summary_fields = array(
    'Name' => 'Name'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', $uploadField = UploadField::create('CategoryImage', 'Image'));
    $uploadField->setDescription('The image shown on the class hub page.');
    $uploadField->setFolderName('class-hub');
    $uploadField->setAllowedExtensions(ALLOWED_IMAGE_EXTENSIONS);

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Teaser', 'Teaser'));
    $editorField->setRows(8);
    $editorField->setDescription('Will be shown on the class hub page. If left blank, the first paragraph of the description will be used.');

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Description', 'Description'));
    $editorField->setRows(14);
    $editorField->setDescription('Will be shown on the class details page.');

    return $fields;
  }
}
