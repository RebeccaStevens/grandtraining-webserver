<?php

/**
 * This contoller manages data request for the site.
 */
abstract class Data_Controller extends Controller {

  /**
   * Echo out the given data as json and set the response Content-Type header to json.
   *
   * @param mixed $data The data to encode as json
   */
  protected function echoJson($data) {
    if (Director::isDev()) {
      // $this->response->addHeader('Access-Control-Allow-Origin', '*');  // seems to have a bug of some sort wtih file_get_contents
      header('Access-Control-Allow-Origin: *');                           // use the standard header function
    }
    $this->response->addHeader('Content-Type', 'application/json');
    echo json_encode($data);
  }

  /**
   * Ensure the request is for a json file.
   * Throws a 404 if the request is not for a json file.
   *
   * @return boolean Whether or not the request was for a json file.
   */
  protected function ensureJsonRequest(SS_HTTPRequest $request) {
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
  protected function getPlaceholderImage(Image $image = null) {
    if ($image === null) {
      return null;
    }
    $scale = 0.01;
    $minSize = min(16, min($image->Width, $image->Height));

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

    return $this->base64encodeImage($image->SetSize($width, $height)->Link());
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
