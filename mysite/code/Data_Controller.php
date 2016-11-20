<?php

/**
 * This contoller manages data request for the site.
 */
class Data_Controller extends Controller {

  private static $url_handlers = array(
    'config' => 'siteConfigData',
    'home' => 'homePageData'
  );

  private static $allowed_actions = array(
    'siteConfigData',
    'homePageData'
  );

  /**
   * Handles request for config.json
   */
  public function siteConfigData(SS_HTTPRequest $request) {
    if (!$this::ensureJsonRequest($request)) {
      return;
    }

    $siteConfig = SiteConfig::current_site_config();

    $company = array(
      'name' => $siteConfig->Title,
      'slogan' => $siteConfig->Tagline,
      'logo' => array(
        'src' => $siteConfig->Logo()->URL,
        'placeholder' => $this->getPlaceholder($siteConfig->Logo())
      )
    );

    $pages = array();
    foreach (WebAppPage::get() as $key => $page) {
      $pages[$page->WebAppPageName] = array(
        'tilte' => $page->Title,
        'menuTitle' => $page->MenuTitle,
        'url' => $page->Link()
      );
    }

    $cities = array();
    foreach (Venue::get() as $key => $venue) {
      if (!array_key_exists($venue->City, $cities)) {
        $cities[$venue->City] = array();
      }
      $cities[$venue->City][$venue->ClientFormattedID()] = array(
        'id' => $venue->GetBase64ID(),
        'city' => $venue->City,
        'name' => $venue->Name,
        'fullname' => $venue->FullName,
      );
    }

    $this->echoJson(array(
      'company' => $company,
      'pages' => $pages,
      'cities' => $cities,
      'links' => array(
        'bookclass' => "/bookings/booknow/bookclass"
      ),
      'countryCode' => "+64"
    ));
  }

  /**
   * Handles request for home.json
   */
  public function homePageData(SS_HTTPRequest $request) {
    if (!$this->ensureJsonRequest($request)) {
      return;
    }

    $homePage = HomePage::get()[0];

    $carouselImages = array();
    foreach ($homePage->CarouselImages() as $key => $image) {
      $sizedImage = $image->SetWidth(1920);
      $carouselImages[] = array(
        'src' => $sizedImage->URL,
        'placeholder' => $this->getPlaceholder($sizedImage)
      );
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

  /**
   * Echo out the given data as json and set the response Content-Type header to json.
   *
   * @param mixed $data The data to encode as json
   */
  private function echoJson($data) {
    $this->response->addHeader('Content-Type', 'application/json');
    echo json_encode($data);
  }

  /**
   * Ensure the request is for a json file.
   * Throws a 404 if the request is not for a json file.
   *
   * @return boolean Whether or not the request was for a json file.
   */
  private function ensureJsonRequest(SS_HTTPRequest $request) {
    if ($request->getExtension() === 'json') {
      return true;
    }
    $this->httpError(404);
    return false;
  }

  /**
   * Get a base64 encoded placeholder image.
   *
   * @param Image $image The image to get a placeholder for
   */
  private function getPlaceholder(Image $image) {
    $scale = 0.01;
    $minSize = 16;

    $width = $image->getWidth() * $scale;
    $height = $image->getHeight() * $scale;

    // ensure that the width and height are atleast `$minSize`
    if ($width < $minSize) {
      $height = $height * $minSize / $width;
      $width = $minSize;
    }
    if ($height < $minSize) {
      $width = $width * $minSize / $height;
      $height = $minSize;
    }

    // make `$width` and `$height` integers
    $width = round($width);
    $height = round($height);

    return $this->base64encodeImage($image->SetSize($width, $height)->URL);
  }

  /**
   * Get a base64 encoding of an image.
   *
   * @param string $image The url to an image to encode
   */
  private function base64encodeImage($image) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $image;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
  }
}
