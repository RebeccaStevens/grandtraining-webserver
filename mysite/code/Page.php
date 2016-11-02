<?php
class Page extends SiteTree {

	private static $db = array(
	);

	private static $has_one = array(
	);

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root.Content.Main","Content");
		$fields->removeFieldFromTab("Root.Content.Main","Metadata");
    return $fields;
  }

}
class Page_Controller extends ContentController {
	private static $allowed_actions = array (
	);

}
