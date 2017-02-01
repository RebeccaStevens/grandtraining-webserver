<?php

use SilverStripe\Reports\Report;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\Versioning\Versioned;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\DropdownField;

class ClassesNotInAnyCategory extends Report {

  public function title() {
    return 'Classes not in any category';
  }

  public function sourceRecords($params = null) {
    $result = ArrayList::create();

    foreach (HolidayClass::get()->sort('Title') as $hc) {
      if ($hc->ClassCategories()->count() === 0) {
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