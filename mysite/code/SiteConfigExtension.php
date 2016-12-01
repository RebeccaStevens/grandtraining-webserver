<?php
class SiteConfigExtension extends DataExtension {

  public static $db = array(
    'BookingsEmail' => 'Varchar(256)',
    'ContactEmail' => 'Varchar(256)',

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

    $fields->addFieldsToTab('Root.Main', HeaderField::create(null, 'Company Settings'), 'Title');
    $fields->addFieldsToTab('Root.Main', UploadField::create('Logo', 'Logo'), 'Title');
    $fields->removeFieldFromTab('Root.Main', 'Tagline');

    $fields->addFieldsToTab('Root.Main', HeaderField::create(null, 'Email Settings'));
    $fields->addFieldsToTab('Root.Main', $field = EmailField::create('BookingsEmail', 'Bookings'));
    $field->setDescription('When a user makes a bookings, a notification will be sent to this email address.');
    $fields->addFieldsToTab('Root.Main', $field = EmailField::create('ContactEmail', 'Contact Form'));
    $field->setDescription('Messages sent throught the contact us form will be sent to this address.');

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
