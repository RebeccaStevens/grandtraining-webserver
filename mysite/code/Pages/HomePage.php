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

    $fields->addFieldToTab('Root.Philosophy', TextField::create('PhilosophyCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Philosophy', $editorField = HTMLEditorField::create('PhilosophyCardContent', 'Card\'s Content'));
    $editorField->setRows(15);

    $fields->addFieldToTab('Root.Classes', TextField::create('ClassesCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Classes', $editorField = HTMLEditorField::create('ClassesCardContent', 'Card\'s Content'));
    $editorField->setRows(15);

    $fields->addFieldToTab('Root.Testimonials', TextField::create('TestimonialsCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Testimonials', GridField::create('Testimonials', 'Testimonials', $this->Testimonials(), GridFieldConfig_RecordEditor::create()->addComponent(new GridFieldOrderableRows('SortOrder'))));

    return $fields;
  }
}

class HomePage_Controller extends WebAppPage_Controller {

}
