<?php

use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\UploadField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;

class SiteConfigExtension extends DataExtension {

  private static $db = array(
    'BookingsEmail' => 'Varchar(256)',
    'ContactEmail' => 'Varchar(256)',

    'FacebookLink' => 'Varchar(256)',
    'GoogleLink' => 'Varchar(256)',
    'TwitterLink' => 'Varchar(256)',

    'GoogleMapsZoom' => 'Int',
    'GoogleMapsApiKey' => 'Varchar',
    'RecaptchaApiSiteKey' => 'Varchar',
    'RecaptchaApiSecretKey' => 'Varchar',

    'EtrainTitle' => 'Varchar(256)',
    'EtrainMenuTitle' => 'Varchar(256)',
    'EtrainLink' => 'Varchar(256)'
  );

  private static $has_one = array(
    'Logo' => 'SilverStripe\Assets\Image'
  );

  public function updateCMSFields(FieldList $fields) {
    $fields->removeFieldFromTab('Root.Main', 'Theme');

    $fields->insertBefore(new Tab('Social', 'Social Media'), 'Access');
    $fields->insertBefore(new TabSet('3rdParty', '3rd Party'), 'Access');

    $fields->addFieldsToTab('Root.Main', HeaderField::create(null, 'Company Settings'), 'Title');
    $fields->addFieldsToTab('Root.Main', $uploadfield = UploadField::create('Logo', 'Logo'), 'Title');
    $uploadfield->setDescription('The Logo of the site.');
    $uploadfield->setFolderName('site');
    $uploadfield->setAllowedExtensions(ALLOWED_IMAGE_EXTENSIONS);

    $fields->removeFieldFromTab('Root.Main', 'Tagline');

    $fields->addFieldsToTab('Root.Main', HeaderField::create(null, 'Email Settings'));
    $fields->addFieldsToTab('Root.Main', $field = EmailField::create('BookingsEmail', 'Bookings'));
    $field->setDescription('When a user makes a bookings, a notification will be sent to this email address.');
    $fields->addFieldsToTab('Root.Main', $field = EmailField::create('ContactEmail', 'Contact Form'));
    $field->setDescription('Messages sent throught the contact us form will be sent to this address.');

    $fields->addFieldsToTab('Root.Main', HeaderField::create(null, 'eTrain Settings'));
    $fields->addFieldsToTab('Root.Main', TextField::create('EtrainTitle', 'Title'));
    $fields->addFieldsToTab('Root.Main', TextField::create('EtrainMenuTitle', 'Link Text'));
    $fields->addFieldsToTab('Root.Main', TextField::create('EtrainLink', 'Link'));

    $fields->addFieldsToTab('Root.Social', array(
      HeaderField::create(null, 'Social Media Networks'),
      TextField::create('FacebookLink', 'Facebook Page'),
      TextField::create('GoogleLink', 'Google+ Account'),
      TextField::create('TwitterLink', 'Twitter Account')
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
