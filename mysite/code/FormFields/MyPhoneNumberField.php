<?php

class MyPhoneNumberField extends PhoneNumberField {

	public function __construct($name, $title = null, $value = '') {
		parent::__construct($name, $title, $value, null, true, null);
	}

	/**
	 * @param array $properties
	 * @return FieldGroup|HTMLText
	 */
	public function Field($properties = array()) {
    Requirements::css('mysite/code/FormFields/MyPhoneNumberField.css');

		$fields = new FieldGroup($this->name);
		$fields->setID("{$this->name}_Holder");
		list($countryCode, $areaCode, $phoneNumber, $extension) = $this->parseValue();

		if ($this->value == '') {
			$countryCode = $this->countryCode;
			$areaCode = $this->areaCode;
			$extension = $this->ext;
		}

		$label = new LabelField(null, '(');
		$label->addExtraClass('phone-number-label');
		$fields->push($label);
		$fields->push(new NumericField($this->name.'[Area]', '', $areaCode, 4));
		$label = new LabelField(null, ')');
		$label->addExtraClass('phone-number-label');

		$fields->push($label);
		$fields->push(new NumericField($this->name.'[Number]', '', $phoneNumber, 10));

		$description = $this->getDescription();
		if ($description) {
      $fields->getChildren()->First()->setDescription($description);
    }

		foreach ($fields as $field) {
			$field->setDisabled($this->isDisabled());
			$field->setReadonly($this->isReadonly());
		}

		return $fields;
	}
}
