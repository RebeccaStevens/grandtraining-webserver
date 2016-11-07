<?php
class HomePage extends WebAppPage {
  private static $can_be_root = true;
  private static $allowed_children = array();

  private static $db = array(
    'Welcome' => 'Varchar(256)',
    'PhilosophyCardHeading' => 'Varchar(128)',
    'PhilosophyCardContent' => 'HTMLText',
    'ClassesCardHeading' => 'Varchar(128)',
    'ClassesCardContent' => 'HTMLText',
    'TestimonialsCardHeading' => 'Varchar(128)'
  );

  private static $has_many = array(
    'Testimonials' => 'Testimonial'
  );

  private static $many_many = array(
    'CarouselImages' => 'Image'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('Welcome', 'Welcome'));

    $fields->addFieldToTab('Root.CarouselImages', UploadField::create('carouselImages', 'Carousel Images'));

    $fields->addFieldToTab('Root.Cards', TextField::create('PhilosophyCardHeading', 'Philosophy Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('PhilosophyCardContent', 'Philosophy Card\'s Content'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.Cards', TextField::create('ClassesCardHeading', 'Classes Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('ClassesCardContent', 'Classes Card\'s Content'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.Cards', TextField::create('TestimonialsCardHeading', 'Testimonials Card\'s Heading'));
    // $fields->addFieldToTab('Root.Cards', GridField::create('Testimonials', 'Testimonials'));

    return $fields;
  }
}

class HomePage_Controller extends WebAppPage_Controller {

}
