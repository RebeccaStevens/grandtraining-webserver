<?php

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Assets\Image;

/**
 * This contoller manages data request for the site.
 */
abstract class DataController extends Controller {

  /**
   * Echo out the given data as json and set the response Content-Type header to json.
   *
   * @param mixed $data The data to encode as json
   */
  protected function echoJson($data) {
    if (Director::isDev()) {
      header('Access-Control-Allow-Origin: *');
    }
    header('Content-Type: application/json');
    echo json_encode($data);
  }

  /**
   * Ensure the request is for a json file.
   * Throws a 404 if the request is not for a json file.
   *
   * @return boolean Whether or not the request was for a json file.
   */
  protected function ensureJsonRequest(HTTPRequest $request) {
    if ($request->getExtension() === 'json') {
      return true;
    }
    $this->httpError(404);
    return false;
  }

  /**
   * Get the image data for the client.
   *
   * @param Image $image The image to get the data for
   * @return Array
   */
  protected function getImageData(Image $image = null) {
    if ($image === null) {
      return array(
        'src' => null,
        'placeholder' => null
      );
    }
    return array(
      'src' => $image->AbsoluteLink(),
      'placeholder' => $this->getPlaceholderImage($image)
    );
  }

  /**
   * Get a base64 encoded placeholder image.
   *
   * @param Image $image The image to get a placeholder for
   */
  protected function getPlaceholderImage(Image $image = null) {
    if ($image === null || $image->Width == 0 || $image->Height == 0) {
      return null;
    }

    $scale = 0.01;
    $minSize = max(16, min($image->Width, $image->Height));

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

    return $this->base64encodeImage($image->FillMax($width, $height)->getURL());
  }

  /**
   * Get a base64 encoding of an image.
   *
   * @param string $image The url to an image to encode
   */
  protected function base64encodeImage($image) {
    $path = $_SERVER['DOCUMENT_ROOT'] . $image;
    $type = pathinfo($path, PATHINFO_EXTENSION);
    $data = file_get_contents($path);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
  }
}
