<?php
class PaperCard extends DataObject {

  private static $db = array(
    'Heading' => 'Varchar',
    'Content' => 'HTMLText'
  );
}
