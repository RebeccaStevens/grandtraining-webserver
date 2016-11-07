<?php
class HolidayClass extends DataObject {

  private static $db = array(
    'Title' => 'Varchar(128)',
    'Level' => 'Enum(\'Beginner, Intermediate, Advanced\',\'Beginner\')',
    'MinAge' => 'Int',
    'MaxAge' => 'Int',
    'Description' => 'HTMLText'
  );

  private static $has_one = array(
    'Banner' => 'Image'
  );

  private static $has_many = array(
    'BookingDates' => 'BookingDateRange'
  );

  private static $belongs_many_many = array(
    'ClassCategories' => 'ClassCategory'
  );

  private static $defaults = array(
    'MinAge' => 5,
    'MaxAge' => 17
  );

  private static $summary_fields = array(
    'Title' => 'Title',
    'Level' => 'Level',
    'MinAge' => 'Min Age',
    'MaxAge' => 'Max Age'
  );

  private static $searchable_fields = array(
    'Title',
    'Level',
    'MinAge',
    'MaxAge',
    'Description'
  );

  public function getCMSFields() {
    $fields = FieldList::create(TabSet::create('Root'));
    $fields->addFieldsToTab('Root.Main', array(
      UploadField::create('Banner', 'Banner Image'),
      TextField::create('Title', 'Title'),
      ListboxField::create('ClassCategories', 'Class Categories')->setMultiple(true)->setSource(ClassCategory::get()->map('ID', 'Name')->toArray()),
      DropdownField::create('Level', 'Level', singleton('HolidayClass')->dbObject('Level')->enumValues()),
      NumericField::create('MinAge', 'Min Age'),
      NumericField::create('MaxAge', 'Max Age'),
      HTMLEditorField::create('Description', 'Description')
    ));

    return $fields;
  }
}
