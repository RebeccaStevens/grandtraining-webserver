<?php

use SilverStripe\Reports\Report;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;

class ClassesWithNoVenues extends Report {

  public function title() {
    return 'Classes not available at any venue';
  }

  public function sourceRecords($params = null) {
    $result = ArrayList::create();

    foreach (HolidayClass::get() as $hc) {
      if ($hc->AvailableVenues()->count() === 0) {
        $result->push($hc);
      }
    }

    return $result;
  }

  public function columns() {
    $fields = array(
      'Title' => 'Title'
    );

    return $fields;
  }
}
