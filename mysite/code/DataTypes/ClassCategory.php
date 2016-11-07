<?php
class ClassCategory extends DataObject {

  private static $db = array(
    'Name' => 'Varchar(128)',
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
    $fields = FieldList::create(TabSet::create('Root'));

    $fields->addFieldToTab('Root.Cards', TextField::create('Name', 'Category Name'));

    $fields->addFieldToTab('Root.Cards', UploadField::create('CategoryImage', 'Category Image'));

    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('Teaser', 'Teaser'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('Description', 'Description'));
    $editorField->setRows(25);

    return $fields;
  }
}
