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

  private static $belongs_many_many = array(
    'AvailableHolidayClasses' => 'HolidayClass'
  );

  private static $summary_fields = array(
    'FullName' => 'Full Venue Name',
    'City' => 'City',
    'Name' => 'Short Venue Name',
    'ContactNumber' => 'Contact Number',
    'EmailAddress' => 'Email'
  );

  private static $searchable_fields = array(
    'City' => array(
      'title' => 'City',
      'field' => 'TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'Name' => array(
      'title' => 'Short Venue Name',
      'field' => 'TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'FullName' => array(
      'title' => 'Full Venue Name',
      'field' => 'TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'ContactNumber' => array(
      'title' => 'Contact Number',
      'field' => 'TextField',
      'filter' => 'ExactMatchFilter'
    ),
    'EmailAddress' => array(
      'title' => 'Email Address',
      'field' => 'TextField',
      'filter' => 'ExactMatchFilter'
    ),
    'Notes' => array(
      'title' => 'Notes',
      'field' => 'TextField',
      'filter' => 'PartialMatchFilter'
    )
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('City', 'City'),
      TextField::create('Name', 'Short Venue Name'),
      TextField::create('FullName', 'Full Venue Name'),
      TextareaField::create('Address', 'Address'),
      PhoneNumberField::create('ContactNumber', 'Contact Number'),
      EmailField::create('EmailAddress', 'Email'),
      TextField::create('Notes', 'Notes'),
      NumericField::create('Latitude', 'Latitude'),
      NumericField::create('Longitude', 'Longitude')
    ));

    return $fields;
  }
}
