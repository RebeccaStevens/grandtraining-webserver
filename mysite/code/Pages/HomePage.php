<?php
class HomePage extends Page {
  private static $can_be_root = true;
  private static $allowed_children = array();

  private static $db = array(
    'welcome' => 'Varchar'
  );

  private static $has_one = array(
    'classesCard' => 'PaperCard',
    'philosophyCard' => 'PaperCard',
    'testimonialsCard' => 'TestimonialsCard'
  );

  private static $many_many = array(
    'carouselImages' => 'Image'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('welcome', 'Welcome', $this->welcome));

    $fields->addFieldToTab('Root.CarouselImages', UploadField::create('carouselImages', 'Carousel Images', $this->carouselImages));

    $fields->addFieldToTab('Root.Cards', TextField::create('philosophyCard.heading', 'Philosophy Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('philosophyCard.content', 'Philosophy Card\'s Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('classesCard.heading', 'Classes Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('classesCard.content', 'Classes Card\'s Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('testimonialsCard.heading', 'Testimonials Card\'s Heading'));
    // $fields->addFieldToTab('Root.Cards', GridField::create('testimonialsCard.testimonials', 'Testimonials Card\'s Content'));

    return $fields;
  }
}

class HomePage_Controller extends Page_Controller {

}
