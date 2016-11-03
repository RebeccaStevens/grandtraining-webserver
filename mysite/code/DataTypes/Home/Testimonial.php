<?php
class Testimonial extends DataObject {

  private static $db = array(
    'by' => 'Varchar',
    "date" => "Date",
    "content" => "Varchar"
  );
}
