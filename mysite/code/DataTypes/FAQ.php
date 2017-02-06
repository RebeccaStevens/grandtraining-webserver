<?php

use SilverStripe\Forms\TextField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

class FAQ extends DataObjectClient {

  private static $singular_name = 'FAQ';
  private static $plural_name = 'FAQs';

  private static $db = array(
    'SortOrder' => 'Int',
    'Question' => 'Varchar(256)',
    'Answer' => 'HTMLText'
  );

  private static $default_sort = 'SortOrder';

  private static $summary_fields = array(
    'Question' => 'Question',
    'Answer.Summary' => 'Answer',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'Question' => 'PartialMatchFilter',
    'Answer' => 'PartialMatchFilter'
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
