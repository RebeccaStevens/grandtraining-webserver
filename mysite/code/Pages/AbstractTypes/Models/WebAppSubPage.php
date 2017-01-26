<?php

class WebAppSubPage extends WebAppPage {

	public function getCMSFields() {
		$fields = parent::getCMSFields();

		$fields->removeFieldFromTab('Root.Main', 'Web App Page Name');

    return $fields;
  }
}
