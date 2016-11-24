<?php
class FAQ extends DataObjectClient {

  private static $db = array(
    'SortOrder' => 'Int',
    'Question' => 'Varchar(256)',
    'Answer' => 'HTMLText'
  );

  private static $has_one = array(
    'Page' => 'FAQPage'
  );

  private static $summary_fields = array(
    'Question' => 'Question',
    'Answer.Summary' => 'Answer'
  );

  private static $searchable_fields = array(
    'Question'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();
    $fields->addFieldsToTab('Root.Main', array(
      TextField::create('Question', 'Question'),
      HTMLEditorField::create('Answer', 'Answer')
    ));

    return $fields;
  }
}
