<?php
class TestimonialsCard extends DataObject {

  private static $db = array(
    'heading' => 'Varchar'
  );

  private static $has_many = array(
    'testimonials' => 'Testimonial'
  );
}
