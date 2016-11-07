<?php
class Testimonial extends DataObject {

  private static $db = array(
    'Author' => 'Varchar(128)',
    "DateWritten" => "Date",
    "Content" => "Text"
  );

  private static $has_one = array(
    'Page' => 'HomePage'
  );
}
