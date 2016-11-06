<?php
class WebAppPage extends SiteTree {

	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab("Root.Content.Main","Content");
		$fields->removeFieldFromTab("Root.Content.Main","Metadata");
    return $fields;
  }
}

class WebAppPage_Controller extends ContentController {

}
