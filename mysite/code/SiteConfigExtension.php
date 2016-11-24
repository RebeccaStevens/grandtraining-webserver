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
    $fields->insertBefore(new Tab('Social', 'Social Media'), 'Access');
    $fields->insertBefore(new Tab('APIKeys', 'API Keys'), 'Access');

    $fields->addFieldsToTab('Root.Main', UploadField::create('Logo', 'Logo'));

    $fields->addFieldToTab('Root.Main', NumericField::create('GoogleMapsZoom', 'Google Maps Zoom Level'));

    $fields->addFieldsToTab('Root.Social', array(
      TextField::create('FacebookLink', 'Facebook')
    ));

    $fields->addFieldsToTab('Root.APIKeys', array(
      TextField::create('GoogleMapsApiKey', 'Google Maps API Key'),
      TextField::create('RecaptchaApiSiteKey', 'Recaptcha API Site Key'),
      TextField::create('RecaptchaApiSecretKey', 'Recaptcha API Secret Key'),
    ));

    $fields->removeFieldFromTab('Root.Main', 'Theme');
  }

}
