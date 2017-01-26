<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\UploadField;

class HomePage extends WebAppPage {

  public $WebAppPageName = 'home';
  private static $hide_ancestor = 'WebAppPage';
  private static $can_be_root = true;
  private static $allowed_children = array();

  /**
   * Only allow one instance of this page type.
   */
  public function canCreate($member = null, $context = array()) {
    return DataObject::get(__CLASS__)->count() === 0;
  }

  private static $db = array(
    'Heading1' => 'Varchar(256)',
    'Heading2' => 'Varchar(256)',
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
    'CarouselImages' => 'SilverStripe\Assets\Image'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('Heading1', 'Main Header'));
    $fields->addFieldToTab('Root.Main', TextField::create('Heading2', 'Sub Header'));

    $fields->addFieldToTab('Root.CarouselImages', $uploadField = UploadField::create('carouselImages', 'Carousel Images'));
    $uploadField->setDescription('The main images on the home page.');
    $uploadField->setFolderName('carousel');
    $uploadField->setAllowedExtensions(ALLOWED_IMAGE_EXTENSIONS);

    $fields->addFieldToTab('Root.Cards.Philosophy', TextField::create('PhilosophyCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards.Philosophy', $editorField = HTMLEditorField::create('PhilosophyCardContent', 'Card\'s Content'));
    $editorField->setRows(15);

    $fields->addFieldToTab('Root.Cards.Classes', TextField::create('ClassesCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards.Classes', $editorField = HTMLEditorField::create('ClassesCardContent', 'Card\'s Content'));
    $editorField->setRows(15);

    $fields->addFieldToTab('Root.Cards.Testimonials', TextField::create('TestimonialsCardHeading', 'Card\'s Heading'));
    $fields->addFieldToTab('Root.Cards.Testimonials', GridField::create('Testimonials', 'Testimonials', $this->Testimonials(), GridFieldConfig_RecordEditor::create()->addComponent(new GridFieldOrderableRows('SortOrder'))));

    return $fields;
  }
}
