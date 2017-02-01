<?php

use SilverStripe\Core\Convert;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\CheckboxSetField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

class Testimonial extends DataObjectClient {

  private static $db = array(
    'SortOrder' => 'Int',
    'Shown' => 'Boolean',
    'Author' => 'Varchar(128)',
    'DateWritten' => 'Date',
    'Content' => 'HTMLText'
  );

  private static $summary_fields = array(
    'DateWritten.Nice' => 'Date Written',
    'Author' => 'Author',
    'Shown.Nice' => 'Show This Testimonial?',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'Shown',
    'Author',
    'DateWritten',
    'Content'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', TextField::create('Author', 'Author'));

    $dateField = DateField::create('DateWritten', 'Date Written')
      ->setConfig('dateformat', 'd/M/yyyy')
      ->setConfig('max', date('c'));
    $dateField->setDescription(sprintf('e.g. %s', Convert::raw2xml(Zend_Date::now()->toString($dateField->getConfig('dateformat')))));
		$dateField->setAttribute('placeholder', $dateField->getConfig('dateformat'));
    $fields->addFieldToTab('Root.Main', $dateField);

    $fields->addFieldToTab('Root.Main', CheckboxSetField::create('Shown', 'Shown', array('1' => '')));

    $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Content', 'Testimonial'));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'Author',
      'DateWritten',
      'Content'
    ));
  }
}
