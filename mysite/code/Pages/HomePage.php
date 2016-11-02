<?php
class HomePage extends Page {
  private static $can_be_root = true;
  private static $allowed_children = array();

  private static $db = array(
    'Welcome' => 'Varchar'
  );

  private static $has_one = array (
    'ClassesCard' => 'PaperCard',
    'PhilosophyCard' => 'PaperCard',
    'TestimonialsCard' => 'TestimonialsCard'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Cards', TextField::create('PhilosophyCard.Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('PhilosophyCard.Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('ClassesCard.Heading'));
    $fields->addFieldToTab('Root.Cards', $editorField = HTMLEditorField::create('ClassesCard.Content'));
    $editorField->setRows(3);

    $fields->addFieldToTab('Root.Cards', TextField::create('TestimonialsCard.Heading'));
    $fields->addFieldToTab('Root.Cards', GridField::create('TestimonialsCard.Testimonials'));

    return $fields;
  }
}

class HomePage_Controller extends Page_Controller {

}
