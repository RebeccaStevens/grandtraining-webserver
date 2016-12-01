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
    $fields->addFieldToTab('Root.Main', TextField::create('City', 'City'));
    $fields->addFieldToTab('Root.Main', $field = TextField::create('Name', 'Short Venue Name')
      ->setDescription('The name of this venue (not including the city).'));
    $fields->addFieldToTab('Root.Main', $field = TextField::create('FullName', 'Full Venue Name')
      ->setDescription('The full name of this venue (short name + city).'));
    $fields->addFieldToTab('Root.Main', TextareaField::create('Address', 'Address'));
    $fields->addFieldToTab('Root.Main', MyPhoneNumberField::create('ContactNumber', 'Contact Number'));
    $fields->addFieldToTab('Root.Main', EmailField::create('EmailAddress', 'Contact Email'));
    $fields->addFieldToTab('Root.Main', TextField::create('Notes', 'Notes'));
    $fields->addFieldToTab('Root.Main', HeaderField::create(null, 'GPS Location (for Google Maps)'));
    $fields->addFieldToTab('Root.Main', NumericField::create('Latitude', 'Latitude'));
    $fields->addFieldToTab('Root.Main', NumericField::create('Longitude', 'Longitude'));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'City',
      'Name',
      'FullName',
      'Latitude',
      'Longitude'
    ));
  }
}
