<?php
class HolidayClass extends DataObjectClient {

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

  private static $many_many = array(
    'AvailableVenues' => 'Venue'
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
    $fields = parent::getCMSFields();

    $categories = ListboxField::create('ClassCategories', 'Class Categories')->setMultiple(true)->setSource(ClassCategory::get()->map('ID', 'Name')->toArray());
    $categories->setDescription('The categories this class should be shown in.');

    $availableVenues = ListboxField::create('AvailableVenues', 'Available Venues')->setMultiple(true)->setSource(Venue::get()->map('ID', 'FullName')->toArray());
    $availableVenues->setDescription('The venues at which this class is avaliable.');

    $fields->addFieldsToTab('Root.Main', array(
      UploadField::create('Banner', 'Banner Image'),
      TextField::create('Title', 'Title'),
      $categories,
      $availableVenues,
      DropdownField::create('Level', 'Level', singleton('HolidayClass')->dbObject('Level')->enumValues()),
      NumericField::create('MinAge', 'Min Age'),
      NumericField::create('MaxAge', 'Max Age'),
      HTMLEditorField::create('Description', 'Description')
    ));

    return $fields;
  }
}
