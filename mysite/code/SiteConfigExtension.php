<?php
class SiteConfigExtension extends DataExtension {

  private static $db = array(
    'facebookLink' => 'Varchar'
  );

  private static $has_one = array(
    'logo' => 'Image'
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->addFieldsToTab('Root.Main', UploadField::create('logo', 'Logo'));

    $fields->addFieldsToTab('Root.Social', array(
      TextField::create('facebookLink', 'Facebook')
    ));

    $fields->removeFieldFromTab('Root.Main', 'Theme');
  }

}
