<?php
/**
 * A DataObject designed to use a random number as its ID.
 */
class DataObjectClient extends DataObject {

  private static $_base64Alphabet = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_';

  /**
   * Ensures that a blank base record exists with the basic fixed fields for this dataobject
   *
   * Does nothing if an ID is already assigned for this record
   *
   * @param string $baseTable Base table
   * @param string $now Timestamp to use for the current time
   */
  protected function writeBaseRecord($baseTable, $now) {
    // Generate new ID if not specified
    if($this->isInDB()) {
      return;
    }

    $maxInsertAttempts = 1000;

    $attempts = 0;
    while ($attempts < $maxInsertAttempts) {
      $attempts++;
      $randID = rand(1, PHP_INT_MAX);     // generate a random id

      // check for profanities in the encoded version of this id
      if (self::containsProfanity($randID)) {
        continue;
      }

      try {
        // Try and perform an insert on the base table
        $insert = new SQLInsert('"' . $baseTable . '"');
        $insert
          ->assign('"ID"', $randID)
          ->assign('"Created"', $now)
          ->execute();
        break;    // if successful, no need to try again
      } catch (SS_DatabaseException $e) {
        continue; // if unsuccessful, try again
      }
    }

    // if the data object was successful inserted
    if ($attempts <= $maxInsertAttempts) {
      $this->changed['ID'] = self::CHANGE_VALUE;
      $this->record['ID'] = DB::get_generated_id($baseTable);
    } else {
      error_log('Unable to insert a ' . get_class($this) . ' into the database (attempts: ' . $attempts . ')');
    }
  }

  /**
   * Get the ID of this object formatted to be displayed by the client.
   */
  public function ClientFormattedID() {
    return self::integerToBase64($this->ID);
  }

  public function getCMSFields() {
    $fields = FieldList::create(TabSet::create('Root'));

    if ($this->isInDB()) {
      $fields->addFieldToTab('Root.Main', ReadonlyField::create(null, 'Client Formatted ID', $this->ClientFormattedID()));
    }

    return $fields;
  }

  /**
   * Get a base 64 string representation of the given integer.
   *
   * @param integer $num
   * @return string
   */
  public static function integerToBase64($num) {
    $b = strlen(self::$_base64Alphabet);
    $r = $num % $b;
    $res = self::$_base64Alphabet[$r];
    $q = floor($num / $b);
    while ($q) {
      $r = $q % $b;
      $q = floor($q / $b);
      $res = self::$_base64Alphabet[$r] . $res;
    }
    return $res;
  }

  /**
   * Get a integer from the string representation of a number in base 64.
   */
  public static function integerFromBase64($num) {
    $b = strlen(self::$_base64Alphabet);
    $limit = strlen($num);
    $res = strpos(self::$_base64Alphabet, $num[0]);
    for ($i = 1; $i < $limit; $i++) {
      $res = $b * $res + strpos(self::$_base64Alphabet, $num[$i]);
    }
    return $res;
  }

  /**
   * Check if the given text contains profanities.
   *
   * @return $boolean
   */
  private static function containsProfanity($text) {
    $result = file_get_contents('http://www.purgomalum.com/service/containsprofanity?text=' . urlencode($text));
    if ($result === 'true') {
      return true;
    }
    if ($result === 'false') {
      return false;
    }

    // if file fails to load
    if ($result === false) {
      error_log('Unable to check for profanity in "' . $text . '", failed to connect to www.purgomalum.com');
    }
    // or a unknown response is given
    else {
      error_log('Unknown response received for profanity check of "' . $text . '" from www.purgomalum.com');
    }
    return false;
  }
}
