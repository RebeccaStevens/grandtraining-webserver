<?php
class ClassCategory extends DataObjectClient {

  private static $db = array(
    'Name' => 'Varchar(128)',
    'URLSegment' => 'Varchar(255)',
    'Teaser' => 'HTMLVarchar(512)',
    'Description' => 'HTMLText'
  );

  private static $indexes = array(
    'UniqieURLSegment' => 'unique(URLSegment)'
  );

  private static $has_one = array(
    'CategoryImage' => 'Image'
  );

  private static $many_many = array(
    'HolidayClasses' => 'HolidayClass'
  );

  private static $summary_fields = array(
    'Name' => 'Name'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('Name', 'Category Name'));

    $fields->addFieldToTab('Root.Main', SiteTreeURLSegmentField::create('URLSegment', 'URL Segment')
			->setURLPrefix(Director::absoluteBaseURL() . 'classes/')
    );

    $fields->addFieldToTab('Root.Main', UploadField::create('CategoryImage', 'Category Image'));

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Teaser', 'Teaser'));
    $editorField->setRows(10);

    $fields->addFieldToTab('Root.Main', $editorField = HTMLEditorField::create('Description', 'Description'));
    $editorField->setRows(25);

    return $fields;
  }

  protected function onBeforeWrite() {
		parent::onBeforeWrite();

    // URLSegment must be lowercase
    if ($this->URLSegment) {
      $this->URLSegment = strtolower($this->URLSegment);
    }

    // set a default URLSegment based on the Title if there isn't one
		if (!$this->URLSegment && $this->Title) {
			$this->URLSegment = str_replace(' ', '-', strtolower($this->Title));
		}
	}
}
