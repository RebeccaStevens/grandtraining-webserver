<?php
class TestimonialsCard extends DataObject {

  private static $db = array(
    'Heading' => 'Varchar'
  );

  private static $has_many = array(
    'Testimonials' => 'Testimonial'
  );
}
