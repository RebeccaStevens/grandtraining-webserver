<?php

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

class FAQ extends DataObjectClient {

  private static $db = array(
    'SortOrder' => 'Int',
    'Question' => 'Varchar(256)',
    'Answer' => 'HTMLText'
  );

  private static $summary_fields = array(
    'Question' => 'Question',
    'Answer.Summary' => 'Answer',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'Question' => 'PartialMatchFilter'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('Question', 'Question'),
      HTMLEditorField::create('Answer', 'Answer')
    ));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'Question',
      'Answer'
    ));
  }
}
