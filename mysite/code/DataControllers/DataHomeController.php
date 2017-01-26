<?php

use SilverStripe\Control\HTTPRequest;

class DataHomeController extends DataController {

  private static $url_handlers = array('' => 'getData');
  private static $allowed_actions = array('getData');

  /**
   * Handles request for this data
   */
  public function getData(HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $homePage = HomePage::get()[0];

    $carouselImages = array();
    foreach ($homePage->CarouselImages() as $key => $image) {
      $sizedImage = $image->ScaleWidth(1920);
      $carouselImages[] = $this->getImageData($sizedImage);
    }

    $philosophy = array(
      'heading' => $homePage->PhilosophyCardHeading,
      'content' => $homePage->PhilosophyCardContent
    );

    $classes = array(
      'heading' => $homePage->ClassesCardHeading,
      'content' => $homePage->ClassesCardContent
    );

    $testimonials = array(
      'heading' => $homePage->TestimonialsCardHeading,
      'list' => array()
    );
    foreach ($homePage->Testimonials() as $key => $testimonial) {
      $testimonials['list'][] = array(
        'by' => $testimonial->Author,
        'date' => $testimonial->DateWritten,
        'content' => $testimonial->Content
      );
    }

    $this->echoJson(array(
      'heading1' => $homePage->Heading1,
      'heading2' => $homePage->Heading2,
      'carouselImages' => $carouselImages,
      'philosophy' => $philosophy,
      'testimonials' => $testimonials,
      'classes' => $classes
    ));
  }

}
