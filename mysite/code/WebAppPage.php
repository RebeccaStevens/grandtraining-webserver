<?php
class WebAppPage extends SiteTree {

	public $WebAppPageName = null;

  /**
   * This is an abstract class.
   * It cannot be craeted.
   */
  public function canCreate($member = null) {
    return false;
  }

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', ReadonlyField::create(null, 'Web App Page Name', $this->WebAppPageName), 'Title');

		$fields->removeFieldFromTab('Root.Main', 'Content');
		$fields->removeFieldFromTab('Root.Main', 'Metadata');

    return $fields;
  }
}

class WebAppPage_Controller extends ContentController {

}
