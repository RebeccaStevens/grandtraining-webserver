<?php
class Venue extends DataObject {

  private static $db = array(
    'City' => 'Varchar(100)',
    'Name' => 'Varchar(100)',
    'Fullname' => 'Varchar(256)',
    'Address' => 'Varchar(256)',
    'ContactNumber' => 'Varchar',
    'EmailAddress' => 'Varchar(256)',
    'Notes' => 'Varchar(256)',
    'Latitude' => 'Float',
    'Longitude' => 'Float'
  );

  private static $summary_fields = array(
    'Fullname' => 'Full Venue Name',
    'City' => 'City',
    'Name' => 'Short Venue Name',
    'ContactNumber' => 'Contact Number',
    'EmailAddress' => 'Email'
  );

  private static $searchable_fields = array(
    'City',
    'Name',
    'Fullname',
    'ContactNumber',
    'EmailAddress'
  );

  public function getCMSFields() {
    $fields = FieldList::create(TabSet::create('Root'));
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('City', 'City'),
      TextField::create('Name', 'Short Venue Name'),
      TextField::create('Fullname', 'Full Venue Name'),
      TextareaField::create('Address', 'Address'),
      PhoneNumberField::create('ContactNumber', 'Contact Number'),
      EmailField::create('Email', 'Email'),
      TextField::create('Notes', 'Notes'),
      NumericField::create('Latitude', 'Latitude'),
      NumericField::create('Longitude', 'Longitude')
    ));

    return $fields;
  }
}
