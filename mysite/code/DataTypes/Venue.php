<?php
class Venue extends DataObjectClient {

  private static $db = array(
    'City' => 'Varchar(100)',
    'Name' => 'Varchar(100)',
    'FullName' => 'Varchar(256)',
    'Address' => 'Varchar(256)',
    'ContactNumber' => 'Varchar',
    'EmailAddress' => 'Varchar(256)',
    'Notes' => 'Varchar(256)',
    'Latitude' => 'Float',
    'Longitude' => 'Float'
  );

  private static $summary_fields = array(
    'FullName' => 'Full Venue Name',
    'City' => 'City',
    'Name' => 'Short Venue Name',
    'ContactNumber' => 'Contact Number',
    'EmailAddress' => 'Email'
  );

  private static $searchable_fields = array(
    'City',
    'Name',
    'FullName',
    'ContactNumber',
    'EmailAddress'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('City', 'City'),
      TextField::create('Name', 'Short Venue Name'),
      TextField::create('FullName', 'Full Venue Name'),
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
