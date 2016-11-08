<?php
class WebAppPage extends SiteTree {

	public $WebAppPageName = null;

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Content.Main', ReadonlyField::create(null, 'Web App Page Name', $this->WebAppPageName), 'Title');

		$fields->removeFieldFromTab('Root.Content.Main', 'Content');
		$fields->removeFieldFromTab('Root.Content.Main', 'Metadata');

    return $fields;
  }
}

class WebAppPage_Controller extends ContentController {

}
