<?php

use SilverStripe\ORM\DataExtension;

class GridFieldExtension extends DataExtension {

  public function updateNewRowClasses(&$classes, $total, $index, $record) {
    if ($record->hasMethod('GridFieldRowClasses')) {
			$classes = array_merge($classes, $record->GridFieldRowClasses());
    }
  }
}
