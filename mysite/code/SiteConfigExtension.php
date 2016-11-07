<?php
class SiteConfigExtension extends DataExtension {

  public static $db = array(
    'FacebookLink' => 'Varchar(256)'
  );

  public static $has_one = array(
    'Logo' => 'Image'
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->addFieldsToTab('Root.Main', UploadField::create('Logo', 'Logo'));

    $fields->addFieldsToTab('Root.Social', array(
      TextField::create('FacebookLink', 'Facebook')
    ));

    $fields->removeFieldFromTab('Root.Main', 'Theme');
  }

}
