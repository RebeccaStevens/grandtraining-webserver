<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\LabelField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class BookingDateRange extends DataObjectClient {

  private static $db = array(
    'StartDate' => 'Date',
    'EndDate' => 'Date',
    'Cost' => 'Currency',
    'Availability' => 'Enum(\'Available, Canceled, Full\',\'Available\')'
  );

  private static $has_one = array(
    'HolidayClass' => 'HolidayClass',
    'Venue' => 'Venue',
  );

  private static $has_many = array(
    'Excludes' => 'ExcludedDate'
  );

  private static $default_sort = 'StartDate DESC';

  private static $summary_fields = array(
    'HolidayClass.Title' => 'Holiday Class',
    'Venue.FullName' => 'Venue',
    'Availability' => 'Availability',
    'StartDate.Nice' => 'Start Date',
    'EndDate.Nice' => 'End Date',
    'RunningStatus' => 'Running',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'HolidayClass.Title' => 'ExactMatchFilter',
    'Venue.FullName' => 'ExactMatchFilter',
    'Availability' => 'ExactMatchFilter',
    'StartDate' => 'ExactMatchFilter',
    'EndDate' => 'ExactMatchFilter',
    'Cost' => 'ExactMatchFilter'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', DropdownField::create('HolidayClassID', 'Class', HolidayClass::get()->map('ID', 'Title')->toArray())->setEmptyString('(Class)'));
    $fields->addFieldToTab('Root.Main', DropdownField::create('VenueID', 'Venue', Venue::get()->map('ID', 'FullName')->toArray())->setEmptyString('(Venue)'));

    $startDateField = DateField::create('StartDate', 'Start Date')
      ->setConfig('dateformat', 'dd/MM/yyyy')
      ->setConfig('min', date('c'));
    $endDateField = DateField::create('EndDate', 'End Date')
      ->setConfig('dateformat', 'dd/MM/yyyy')
      ->setConfig('min', date('c'));

    $startDateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($startDateField->getConfig('dateformat')))));
    $endDateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($endDateField->getConfig('dateformat')))));
		$startDateField->setAttribute('placeholder', $startDateField->getConfig('dateformat'));
    $endDateField->setAttribute('placeholder', $endDateField->getConfig('dateformat'));

    $fields->addFieldToTab('Root.Main', $startDateField);
    $fields->addFieldToTab('Root.Main', $endDateField);

    $fields->addFieldToTab('Root.Main', CurrencyField::create('Cost', 'Cost'));

    $fields->addFieldToTab('Root.Main', DropdownField::create('Availability', 'Availability', singleton(__CLASS__)->dbObject('Availability')->enumValues()));

    if ($this->isInDB()) {
      $fields->addFieldToTab('Root.Main', GridField::create('Excludes', 'Excluded Dates', $this->Excludes(), GridFieldConfig_RecordEditor::create()));
    } else {
      $fields->addFieldToTab('Root.Main', HeaderField::create(null, 'Excluded Dates'));
      $fields->addFieldToTab('Root.Main', LabelField::create(null, 'Excluded Dates can only be added after this Booking Date Range has been created.')->addExtraClass('form__field-holder form__field-extra-label'));
    }

    return $fields;
  }

  public function getCMSValidator() {
    return new class(array('HolidayClassID', 'VenueID', 'StartDate','EndDate','Cost','Availability','HolidayClassID')) extends RequiredFields {

      function php($data) {
        $valid = parent::php($data);

        if (!isset($data['EndDate']) || date_create($data['EndDate']) < date_create($data['StartDate'])) {
          $valid = false;
          $this->validationError('EndDate','"EndDate" cannot be before "StartDate"');
        }

        if (!isset($data['Cost']) || $data['Cost'] <= 0) {
          $valid = false;
          $this->validationError('Cost','"Cost" must be grater than $0.00');
        }

        return $valid;
      }
    };
  }

  public function getRunningStatus() {
    $startDate = date_create($this->StartDate);
    $endDate = date_create($this->EndDate);
    $now = date_create(date_create()->format('Y-m-d'));

    if ($now >= $startDate && $now <= $endDate) {
      return 'Running';
    }
    if ($now < $startDate) {
      return 'Future Class';
    }
    return 'Past Class';
  }

  public function GridFieldRowClasses() {
    switch ($this->getRunningStatus()) {
      case 'Past Class':
        return array('past-class');
      case 'Running':
        return array('running-class');
      case 'Future Class':
        return array('future-class');
      default:
        return array();
    }
  }

  public function scaffoldSearchFields($_params = null) {
    $fields = parent::scaffoldSearchFields($_params);

    $holidayClassDropdown = DropdownField::create(
      'HolidayClass.ID',
      'Class',
      HolidayClass::get()->map('ID', 'Title')->toArray()
    );
    $holidayClassDropdown->setEmptyString('(Any)');

    $fields->replaceField('HolidayClass__Title', $holidayClassDropdown);

    $venueDropdown = DropdownField::create(
      'Venue.ID',
      'Venue',
      Venue::get()->map('ID', 'FullName')->toArray()
    );
    $venueDropdown->setEmptyString('(Any)');

    $fields->replaceField('Venue__FullName', $venueDropdown);

    return $fields;
  }
}

class ExcludedDate extends DataObject {

  private static $db = array(
    'Date' => 'Date'
  );

  private static $has_one = array(
    'BookingDateRange' => 'BookingDateRange'
  );

  private static $default_sort = 'Date';

  private static $summary_fields = array(
    'Date.Nice' => 'Date'
  );

  private static $searchable_fields = array(
    'Date' => 'ExactMatchFilter'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->removeFieldFromTab('Root.Main', 'BookingDateRangeID');

    $fields->addFieldToTab('Root.Main', ReadonlyField::create(null, 'Booking Date', $this->BookingDateRange()->ClientFormattedID()));

    $dateField = DateField::create('Date', 'Date')
      ->setConfig('dateformat', 'dd/MM/yyyy')
      ->setConfig('min', date('c'));

    $dateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($dateField->getConfig('dateformat')))));
		$dateField->setAttribute('placeholder', $dateField->getConfig('dateformat'));

    $fields->addFieldToTab('Root.Main', $dateField);

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'Date'
    ));
  }

  public function validate() {
    $result = parent::validate();

    $BDR = $this->BookingDateRange();

    $startDate = date_create($BDR->StartDate);
    $endDate = date_create($BDR->EndDate);
    $date = date_create($this->Date);

    if ($date <= $startDate) {
      $result->addError("Invalid Date: Date must be between: {$startDate->format('d/m/Y')} and {$endDate->format('d/m/Y')} (exclusive).");
    }
    if ($date >= $endDate) {
      $result->addError("Invalid Date: Date must be between: {$startDate->format('d/m/Y')} and {$endDate->format('d/m/Y')} (exclusive).");
    }

    foreach ($BDR->Excludes() as $excluded) {
      if ($excluded->ID != $this->ID) {
        $excludedDate = date_create($excluded->Date);
        if ($date == $excludedDate) {
          $result->addError('Date Already Exist: Cannot set the date to a date that is already excluded.');
        }
      }
    }

    return $result;
  }
}
