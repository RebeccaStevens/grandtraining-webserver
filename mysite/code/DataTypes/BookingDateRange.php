<?php
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
}
