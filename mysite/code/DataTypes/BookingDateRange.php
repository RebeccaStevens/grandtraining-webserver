<?php

use SilverStripe\Core\Convert;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\RequiredFields;

class BookingDateRange extends DataObjectClient {

  private static $db = array(
    'StartDate' => 'Date',
    'EndDate' => 'Date',
    'Cost' => 'Currency',
    'Availability' => 'Enum(\'Available, Canceled, Full\',\'Available\')'
  );

  private static $has_one = array(
    'HolidayClass' => 'HolidayClass'
  );

  private static $has_many = array(
    'Excludes' => 'Date'
  );

  private static $summary_fields = array(
    'HolidayClass.Title' => 'Holiday Class',
    'Availability' => 'Availability',
    'StartDate.Nice' => 'Start Date',
    'EndDate.Nice' => 'End Date',
    'Cost.Nice' => 'Cost',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'Availability' => 'ExactMatchFilter',
    'StartDate' => 'ExactMatchFilter',
    'EndDate' => 'ExactMatchFilter',
    'Cost' => 'ExactMatchFilter',
    'HolidayClass.Title' => 'ExactMatchFilter'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $startDateField = DateField::create('StartDate', 'Start Date')
      ->setConfig('dateformat', 'd/M/yyyy')
      ->setConfig('min', date('c'));
    $endDateField = DateField::create('EndDate', 'End Date')
      ->setConfig('dateformat', 'd/M/yyyy')
      ->setConfig('min', date('c'));

    $startDateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($startDateField->getConfig('dateformat')))));
    $endDateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($endDateField->getConfig('dateformat')))));
		$startDateField->setAttribute('placeholder', $startDateField->getConfig('dateformat'));
    $endDateField->setAttribute('placeholder', $endDateField->getConfig('dateformat'));

    $fields->addFieldToTab('Root.Main', $startDateField);
    $fields->addFieldToTab('Root.Main', $endDateField);

    $fields->addFieldToTab('Root.Main', CurrencyField::create('Cost', 'Cost'));

    $fields->addFieldToTab('Root.Main', DropdownField::create('Availability', 'Availability', singleton(__CLASS__)->dbObject('Availability')->enumValues()));

    $fields->addFieldToTab('Root.Main', DropdownField::create('HolidayClassID', 'Holiday Class', HolidayClass::get()->map('ID', 'Title')->toArray()));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'StartDate',
      'EndDate',
      'Cost',
      'Availability',
      'HolidayClassID'
    ));
  }
}
