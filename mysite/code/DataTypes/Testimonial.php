<?php
class Testimonial extends DataObject {

  private static $db = array(
    'by' => 'Varchar',
    "date" => "Date",
    "content" => "Varchar"
  );

  private static $has_one = array(
    'page' => 'HomePage'
  );
}
