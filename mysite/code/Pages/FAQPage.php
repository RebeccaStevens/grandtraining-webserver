<?php
class FAQPage extends WebAppPage {
  public $WebAppPageName = 'faq';
  private static $can_be_root = true;
  private static $allowed_children = array ();

  private static $has_many = array(
    'FAQs' => 'FAQ'
  );

  /**
   * Only allow one instance of this page type.
   */
  public function canCreate($member = null) {
    return DataObject::get(__CLASS__)->count() === 0;
  }

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.FAQs', GridField::create('FAQs', 'FAQs', $this->FAQs(), GridFieldConfig_RecordEditor::create()->addComponent(new GridFieldOrderableRows('SortOrder'))));

    return $fields;
  }
}

class FAQPage_Controller extends WebAppPage_Controller {

}
