<?php
class PaperCard extends DataObject {

  private static $db = array(
    'heading' => 'Varchar',
    'content' => 'HTMLText'
  );
}
