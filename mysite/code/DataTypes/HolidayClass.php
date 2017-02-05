<?php

use SilverStripe\Forms\UploadField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\RequiredFields;

class HolidayClass extends DataObjectClient {

  private static $db = array(
    'Title' => 'Varchar(128)',
    'Level' => 'Enum(\'Beginner, Intermediate, Advanced\',\'Beginner\')',
    'MinAge' => 'Int',
    'MaxAge' => 'Int',
    'Description' => 'HTMLText'
  );

  private static $has_one = array(
    'Banner' => 'SilverStripe\Assets\Image'
  );

  private static $has_many = array(
    'BookingDates' => 'BookingDateRange'
  );

  private static $many_many = array(
    'AvailableVenues' => 'Venue'
  );

  private static $belongs_many_many = array(
    'ClassCategories' => 'ClassCategoryPage'
  );

  private static $defaults = array(
    'MinAge' => 5,
    'MaxAge' => 17
  );

  private static $default_sort = 'Title';

  private static $summary_fields = array(
    'Title' => 'Title',
    'Level' => 'Level',
    'MinAge' => 'Min Age',
    'MaxAge' => 'Max Age',
    'PublishedState' => 'Published State'
  );

  private static $searchable_fields = array(
    'Title' => array(
      'title' => 'Title',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'Level' => array(
      'title' => 'Level'
    ),
    'MinAge' => array(
      'title' => 'Min Age',
      'field' => 'SilverStripe\Forms\NumericField',
      'filter' => 'ExactMatchFilter'
    ),
    'MaxAge' => array(
      'title' => 'Max Age',
      'field' => 'SilverStripe\Forms\NumericField',
      'filter' => 'ExactMatchFilter'
    ),
    'Description' => array(
      'title' => 'Description',
      'field' => 'SilverStripe\Forms\TextField',
      'filter' => 'PartialMatchFilter'
    ),
    'AvailableVenues.FullName' => array(),
    'ClassCategories.Title' => array()
  );

  public function getCMSFields() {
    $fields = parent::getCMSFields();

    $banner = UploadField::create('Banner', 'Banner Image');
    $banner->setDescription('The image displayed above the class.');
    $banner->setFolderName('classes');
    $banner->setAllowedExtensions(ALLOWED_IMAGE_EXTENSIONS);

    $categories = ListboxField::create('ClassCategories', 'Class Categories')->setSource(ClassCategoryPage::get()->map('ID', 'Title')->toArray());
    $categories->setDescription('The categories this class should be shown in.');

    $availableVenues = ListboxField::create('AvailableVenues', 'Available Venues')->setSource(Venue::get()->sort('Region, FullName')->map('ID', 'FullName')->toArray());
    $availableVenues->setDescription('The venues at which this class is avaliable.');

    $fields->addFieldsToTab('Root.Main', array(
      $banner,
      TextField::create('Title', 'Title'),
      $categories,
      $availableVenues,
      DropdownField::create('Level', 'Class Level', singleton('HolidayClass')->dbObject('Level')->enumValues()),
      NumericField::create('MinAge', 'Min Recommended Age'),
      NumericField::create('MaxAge', 'Max Recommended Age'),
      HTMLEditorField::create('Description', 'Description')
    ));

    return $fields;
  }

  public function getCMSValidator() {
    return new RequiredFields(array(
      'Title',
      'Level',
      'MinAge',
      'MaxAge',
      'Description'
    ));
  }

  public function scaffoldSearchFields($_params = null) {
    $fields = parent::scaffoldSearchFields($_params);

    $venueDropdown = DropdownField::create(
      'AvailableVenues.ID',
      'Available At',
      Venue::get()->sort('Region, FullName')->map('ID', 'FullName')->toArray()
    );
    $venueDropdown->setEmptyString('(Anywhere/Nowhere)');

    $fields->replaceField('AvailableVenues__FullName', $venueDropdown);

    $categoryDropdown = DropdownField::create(
      'ClassCategories.ID',
      'Category',
      ClassCategoryPage::get()->map('ID', 'Title')->toArray()
    );
    $categoryDropdown->setEmptyString('(Any)');

    $fields->replaceField('ClassCategories__Title', $categoryDropdown);

    return $fields;
  }
}
