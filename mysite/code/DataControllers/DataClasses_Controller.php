<?php
class DataClasses_Controller extends Data_Controller {

  private static $url_handlers = array(
    '' => 'getData',
    '$VenueID/$ClassCategoryPage' => 'getClassCategoryPageData'
  );
  private static $allowed_actions = array(
    'getData',
    'getClassCategoryPageData'
  );

  /**
   * Handles request for this data
   */
  public function getData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $holidayClasses = HolidayClass::get();

    $categories = array();
    foreach (ClassCategoryPage::get() as $category) {
      $sizedImage = $category->CategoryImage()->Fill(250, 250);

      $locationsAvailable = array();
      $locationsAvailableMap = array();
      foreach ($holidayClasses as $hc) {
        foreach ($hc->ClassCategories() as $cat) {
          if ($category->ID === $cat->ID) {
            foreach ($hc->AvailableVenues() as $hcVenue) {
              $locationsAvailableMap[$hcVenue->ClientFormattedID()] = true;
            }
          }
        }
      }
      $locationsAvailable = array_keys($locationsAvailableMap);

      $teaser = $category->Teaser;
      if (!isset($teaser)) {
        if ($category->Description) {
          $description = HTMLText::create();
          $description->setValue($category->Description);
          $teaser = $description->FirstParagraph();
        }
      }

      $categories[] = array(
        'name' => $category->MenuTitle,
        'url' => $category->URLSegment,
        'teaser' => $teaser,
        'image' => $this->getImageData($sizedImage),
        'locationsAvailable' => $locationsAvailable
      );
    }

    $this->echoJson(array(
      'class-categories' => $categories
    ));
  }

  /**
   * Handles request for this data
   */
  public function getClassCategoryPageData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $classesPage = ClassesPage::get()[0];

    $venue = Venue::get_by_id('Venue', DataObjectClient::integerFromBase64($this->getRequest()->param('VenueID')));
    if ($venue === false) {
      $this->httpError(404);
    }

    $ClassCategoryPage = SiteTree::get_by_link(ClassesPage::get()[0]->URLSegment . '/' . $this->getRequest()->param('ClassCategoryPage'));
    if ($ClassCategoryPage === false) {
      $this->httpError(404);
    }

    $classes = array();
    foreach (HolidayClass::get() as $class) {
      $finishedWithThisClass = false;
      foreach ($class->AvailableVenues() as $classVenue) {
        if ($classVenue->ID === $venue->ID) {
          foreach ($class->ClassCategories() as $category) {
            if ($category->ID === $ClassCategoryPage->ID) {
              $sizedImage = $class->Banner()->Fill(700, 140);

              $classes[] = array(
                'title' => $class->Title,
                'level' => $class->Level,
                'image' => $this->getImageData($sizedImage),
                'min-age' => $class->MinAge,
                'max-age' => $class->MaxAge,
                'description' => $class->Description,
                'dates' => array()
              );
              $finishedWithThisClass = true;
              break;
            }
          }
        }
        if ($finishedWithThisClass) {
          break;
        }
      }
    }

    $this->echoJson(array(
      'title' => $ClassCategoryPage->Title,
      'description' => $ClassCategoryPage->Description,
      'classes' => $classes
    ));
  }

}
