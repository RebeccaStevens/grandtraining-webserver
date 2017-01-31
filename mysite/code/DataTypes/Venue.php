<?php

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\PhoneNumberField;
use SilverStripe\Forms\RequiredFields;

class Venue extends DataObjectClient {

  private static $db = array(
    'Region' => 'Varchar(100)',
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
    'FullName' => 'Long Name',
    'Region' => 'Region',
    'Name' => 'Name',
    'FormattedContactNumber' => 'Contact Number',
    'EmailAddress' => 'Email'
  );

  private static $searchable_fields = array(
    'Region' => array(
      'title' => 'Region',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'Name' => array(
      'title' => 'Short Venue Name',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'FullName' => array(
      'title' => 'Full Venue Name',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'ContactNumber' => array(
      'title' => 'Contact Number',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'ExactMatchFilter'
    ),
    'EmailAddress' => array(
      'title' => 'Email Address',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'ExactMatchFilter'
    ),
    'Notes' => array(
      'title' => 'Notes',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    )
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldToTab('Root.Main', TextField::create('Region', 'Region'));
    $fields->addFieldToTab('Root.Main', $field = TextField::create('Name', 'Short Venue Name')
      ->setDescription('The name of this venue (not including the region).'));
    $fields->addFieldToTab('Root.Main', $field = TextField::create('FullName', 'Full Venue Name')
      ->setDescription('The full name of this venue (short name + region).'));
    $fields->addFieldToTab('Root.Main', TextareaField::create('Address', 'Address'));
    $fields->addFieldToTab('Root.Main', PhoneNumberField::create('ContactNumber', 'Contact Number', null, null, true, null));
    $fields->addFieldToTab('Root.Main', EmailField::create('EmailAddress', 'Contact Email'));
    $fields->addFieldToTab('Root.Main', TextField::create('Notes', 'Notes'));
    $fields->addFieldToTab('Root.Main', HeaderField::create(null, 'GPS Location (for Google Maps)'));
    $fields->addFieldToTab('Root.Main', NumericField::create('Latitude', 'Latitude'));
    $fields->addFieldToTab('Root.Main', NumericField::create('Longitude', 'Longitude'));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'Region',
      'Name',
      'FullName',
      'Latitude',
      'Longitude'
    ));
  }

  /**
   * Nicely format the contact number.
   */
  public function getFormattedContactNumber() {
    $phoneNumberField = PhoneNumberField::create('null', null, '', true, true, true);
    $phoneNumberField->setValue($this->obj('ContactNumber'));

    $country = $phoneNumberField->getCountryField()->Value();
    $area = $phoneNumberField->getAreaField()->Value();
    $number = $phoneNumberField->getNumberField()->Value();
    $extension = $phoneNumberField->getExtensionField()->Value();

    $completeNumber = '';
    if (!empty($country)) {
        $completeNumber .= '+' . $country . ' ';
    }

    if (!empty($area)) {
        $completeNumber .= '(' . $area . ') ';
    }

    $numberLength = strlen($number);
    if ($numberLength >= 7) {
      $cutPoint = intval($numberLength / 2);
      if ($cutPoint > 4) {
        $cutPoint = 4;
      }
      $completeNumber .= substr($number, 0, $cutPoint) . ' ' . substr($number, $cutPoint);
    } else {
      $completeNumber .= $number;
    }

    /** @skipUpgrade */
    if (!empty($extension)) {
        $completeNumber .= ' #' . $extension;
    }

    return $completeNumber;
  }
}
