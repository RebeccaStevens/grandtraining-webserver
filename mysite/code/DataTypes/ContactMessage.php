<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TextAreaField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;

class ContactMessage extends DataObject {

  private static $db = array(
    'Name' => 'Varchar(256)',
    'Phone' => 'Varchar(15)',
    'Email' => 'Varchar(256)',
    'Location' => 'Varchar(256)',
    'Subject' => 'Varchar(256)',
    'Message' => 'Text',
    'DealtWith' => 'Boolean',
    'Notes' => 'HTMLText'
  );

  private static $summary_fields = array(
    'Name' => 'Sender\'s Name',
    'Location' => 'Location',
    'Subject' => 'Subject',
    'DealtWith.Nice' => 'Dealt With'
  );

  private static $searchable_fields = array(
    'Name' => 'PartialMatchFilter',
    'Phone' => 'ExactMatchFilter',
    'Email' => 'ExactMatchFilter',
    'Location' => 'ExactMatchFilter',
    'Subject' => 'PartialMatchFilter',
    'Message' => 'PartialMatchFilter',
    'DealtWith' => 'ExactMatchFilter',
    'Notes' => 'PartialMatchFilter'
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $fields->addFieldToTab('Root.Main', CheckboxField::create('DealtWith', 'Message Dealt With'));

    $fields->addFieldToTab('Root.Main', ReadonlyField::create('Name', 'Sender\'s Name'));
    $fields->addFieldToTab('Root.Main', ReadonlyField::create('Phone', 'Phone'));
    $fields->addFieldToTab('Root.Main', ReadonlyField::create('Email', 'Email'));
    $fields->addFieldToTab('Root.Main', ReadonlyField::create('Location', 'Location'));
    $fields->addFieldToTab('Root.Main', ReadonlyField::create('Subject', 'Subject'));
    $fields->addFieldToTab('Root.Main', TextAreaField::create('Message', 'Message')->performReadonlyTransformation());

    $fields->addFieldToTab('Root.Main', HTMLEditorField::create('Notes', 'Notes', '', new HTMLEditorNotesConfig()));

    return $fields;
  }
}
