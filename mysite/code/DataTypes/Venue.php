<?php
class Venue extends DataObject {

  private static $db = array(
    'city' => 'Varchar(100)',
    'name' => 'Varchar(100)',
    'fullname' => 'Varchar(256)',
    'address' => 'Varchar(256)',
    'contactNumber' => 'Varchar',
    'email' => 'Varchar(256)',
    'notes' => 'Varchar(256)',
    'latitude' => 'Float',
    'longitude' => 'Float'
  );

  private static $summary_fields = array(
    'fullname' => 'Full Venue Name',
    'city' => 'City',
    'name' => 'Short Venue Name',
    'contactNumber' => 'Contact Number',
    'email' => 'Email'
  );

  private static $searchable_fields = array(
    'city',
    'name',
    'fullname',
    'contactNumber',
    'email'
  );

  public function getCMSFields() {
    $fields = FieldList::create(TabSet::create('Root'));
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('city', 'City'),
      TextField::create('name', 'Short Venue Name'),
      TextField::create('fullname', 'Full Venue Name'),
      TextareaField::create('address', 'Address'),
      PhoneNumberField::create('contactNumber', 'Contact Number'),
      TextField::create('email', 'Email'),
      TextField::create('notes', 'Notes'),
      NumericField::create('latitude', 'Latitude'),
      NumericField::create('longitude', 'Longitude')
    ));

    return $fields;
  }
}
