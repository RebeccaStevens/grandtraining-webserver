<?php
class SiteConfigExtension extends DataExtension {

  public static $db = array(
    'FacebookLink' => 'Varchar(256)',
    'GoogleMapsZoom' => 'Int',
    'GoogleMapsApiKey' => 'Varchar',
    'RecaptchaApiSiteKey' => 'Varchar',
    'RecaptchaApiSecretKey' => 'Varchar'
  );

  public static $has_one = array(
    'Logo' => 'Image'
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->removeFieldFromTab('Root.Main', 'Theme');

    $fields->insertBefore(new Tab('Social', 'Social Media'), 'Access');
    $fields->insertBefore(new TabSet('3rdParty', '3rd Party'), 'Access');

    $fields->addFieldsToTab('Root.Main', UploadField::create('Logo', 'Logo'));

    $fields->addFieldsToTab('Root.Social', array(
      TextField::create('FacebookLink', 'Facebook')
    ));

    $fields->addFieldsToTab('Root.3rdParty.GoogleMaps', array(
      TextField::create('GoogleMapsApiKey', 'API Key'),
      NumericField::create('GoogleMapsZoom', 'Default Zoom Level')
    ));
    $fields->addFieldsToTab('Root.3rdParty.Recaptcha', array(
      TextField::create('RecaptchaApiSiteKey', 'API Site Key'),
      TextField::create('RecaptchaApiSecretKey', 'API Secret Key')
    ));
  }

}
