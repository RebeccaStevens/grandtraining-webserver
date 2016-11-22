<?php
class DataClasses_Controller extends Data_Controller {

  private static $url_handlers = array(
    '' => 'getData',
    '$VenueID/$ClassCategory' => 'getClassCategoryData'
  );
  private static $allowed_actions = array(
    'getData',
    'getClassCategoryData'
  );

  /**
   * Handles request for this data
   */
  public function getData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $categories = array();
    foreach (ClassCategory::get() as $category) {
      $sizedImage = $category->CategoryImage()->Fill(250, 250);

      $locationsAvailable = array();
      foreach (Venue::get() as $venue) {
        $locationsAvailable[] = $venue->ClientFormattedID();
      }

      $categories[] = array(
        name => $category->Name,
        url => $category->URLSegment,
        teaser => $category->Teaser,
        image => array(
          src => $sizedImage->URL,
          placeholder => $this->getPlaceholderImage($sizedImage)
        ),
        locationsAvailable => $locationsAvailable
      );
    }

    $this->echoJson(array(
      'class-categories' => $categories
    ));
  }

  /**
   * Handles request for this data
   */
  public function getClassCategoryData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $classesPage = ClassesPage::get()[0];

    $venue = Venue::get_by_id('Venue', DataObjectClient::integerFromBase64($this->getRequest()->param('VenueID')));
    if ($venue === false) {
      $this->httpError(404);
    }

    $classCategoryList = ClassCategory::get()->filter(array(
      'URLSegment' => $this->getRequest()->param('ClassCategory')
    ));
    if ($classCategoryList->Count() === 0) {
      $this->httpError(404);
    }
    $classCategory = $classCategoryList[0];

    $classes = array();
    foreach (HolidayClass::get() as $class) {
      $sizedImage = $class->Banner()->Fill(700, 140);

      $classes[] = array(
        title => $class->Title,
        level => $class->Level,
        image => array(
          src => $sizedImage->URL,
          placeholder => $this->getPlaceholderImage($sizedImage)
        ),
        'min-age' => $class->MinAge,
        'max-age' => $class->MaxAge,
        description => $class->Description,
        dates => array()
      );
    }

    $this->echoJson(array(
      'title' => $classCategory->Title,
      'description' => $classCategory->Description,
      'classes' => $classes
    ));
  }

}
