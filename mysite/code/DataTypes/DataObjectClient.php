<?php
/**
 * A DataObject designed to use a random number as its ID.
 */
class DataObjectClient extends DataObject {

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
		if($this->isInDB()) return;

    $maxInsertAttempts = 10;

    for ($i = 0; $i < $maxInsertAttempts; $i++) {
      $randID = rand(1, PHP_INT_MAX);                 // generate a random id

  		// Perform an insert on the base table
  		$insert = new SQLInsert('"'.$baseTable.'"');
  		$result = $insert
        ->assign('"ID"', $randID)
  			->assign('"Created"', $now)
  			->execute();
      if ($result->first()) {
        break;
      }
    }
    $this->changed['ID'] = self::CHANGE_VALUE;
    $this->record['ID'] = DB::get_generated_id($baseTable);
	}

  /**
   * Get the ID of this Object formatted to be given to a client.
   *
   * @return string
   */
  public function GetBase64ID() {
    return self::toBase($this->ID);
  }

  public function getCMSFields() {
    $fields = FieldList::create(TabSet::create('Root'));

    if ($this->isInDB()) {
      $fields->addFieldToTab('Root.Main', ReadonlyField::create(null, 'Formatted ID', $this->GetBase64ID()));
    }

    return $fields;
  }

  private static $_base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-_';

  /**
   * Get a string representation of the given integer in base X,
   * where X is the length of self::$_base.
   *
   * @param integer $num
   * @return string
   */
  public static function toBase($num) {
    $b = strlen(self::$_base);
    $r = $num % $b;
    $res = self::$_base[$r];
    $q = floor($num / $b);
    while ($q) {
      $r = $q % $b;
      $q = floor($q / $b);
      $res = self::$_base[$r] . $res;
    }
    return $res;
  }

  /**
   * Get a number from the string representation of a number in base X,
   * where X is the length of self::$_base.
   */
  public static function fromBase($num) {
    $b = strlen(self::$_base);
    $limit = strlen($num);
    $res = strpos(self::$_base, $num[0]);
    for ($i = 1; $i < $limit; $i++) {
      $res = $b * $res + strpos(self::$_base, $num[$i]);
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
