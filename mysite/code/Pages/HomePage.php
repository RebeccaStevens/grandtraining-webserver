<?php
class HomePage extends WebAppPage {
  private static $can_be_root = true;
  private static $allowed_children = array();

  private static $db = array(
    'welcome' => 'Varchar',
    'philosophyCardHeading' => 'Varchar',
    'philosophyCardContent' => 'HTMLText',
    'classesCardHeading' => 'Varchar',
    'classesCardContent' => 'HTMLText',
    'testimonialsCardHeading' => 'Varchar'
  );

  private static $has_many = array(
    'testimonials' => 'Testimonial'
  );

  private static $many_many = array(
    'carouselImages' => 'Image'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('welcome', 'Welcome'));

    $fields->addFieldToTab('Root.CarouselImages', UploadField::create('carouselImages', 'Carousel Images'));

    $fields->addFieldToTab('Root.Cards', TextField::create('philosophyCardHeading', 'Philosophy Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('philosophyCardContent', 'Philosophy Card\'s Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('classesCardHeading', 'Classes Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('classesCardContent', 'Classes Card\'s Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('testimonialsCardHeading', 'Testimonials Card\'s Heading'));
    // $fields->addFieldToTab('Root.Cards', GridField::create('testimonialsCardTestimonials', 'Testimonials Card\'s Content', $this->testimonialsCard()->content));

    return $fields;
  }
}

class HomePage_Controller extends WebAppPage_Controller {

}
