<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines the owntracks_location entity interface.
 */
interface OwnTracksLocationInterface extends ContentEntityInterface {

  /**
   * Creates an owntracks_location entity from a request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   */
  public static function createFromRequest(Request $request);

  /**
   * Gets a Lat/Lang array.
   *
   * @return array
   */
  public function getLocation();

}
