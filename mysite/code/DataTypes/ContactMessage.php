<?php

use SilverStripe\ORM\DataObject;

class ContactMessage extends DataObject {

  private static $db = array(
    'Name' => 'Varchar(256)',
    'Phone' => 'Varchar(15)',
    'Email' => 'Varchar(256)',
    'Location' => 'Varchar(256)',
    'Subject' => 'Varchar(256)',
    'Message' => 'Text',
    'Done' => 'Boolean',
    'Notes' => 'Text'
  );

  private static $summary_fields = array(
    'Name' => 'Sender\'s Name',
    'Location' => 'Location',
    'Subject' => 'Subject',
    'Done' => 'Action Required'
  );

  private static $searchable_fields = array(
    'Name' => 'PartialMatchFilter',
    'Phone' => 'ExactMatchFilter',
    'Email' => 'ExactMatchFilter',
    'Location' => 'ExactMatchFilter',
    'Subject' => 'PartialMatchFilter',
    'Message' => 'PartialMatchFilter',
    'Done' => 'ExactMatchFilter',
    'Notes' => 'PartialMatchFilter'
  );
}
