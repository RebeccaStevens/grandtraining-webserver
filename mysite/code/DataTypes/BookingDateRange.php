<?php
class BookingDateRange extends DataObject {

  private static $db = array(
    'StartDate' => 'Date',
    'EndDate' => 'Date',
    'Cost' => 'Currency',
    'Availability' => 'Enum(\'available, canceled, full\',\'available\')'
  );

  private static $has_one = array(
    'HolidayClass' => 'HolidayClass'
  );

  private static $has_many = array(
    'Excludes' => 'Date'
  );
}
