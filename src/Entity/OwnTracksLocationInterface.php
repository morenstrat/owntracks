<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines the owntracks_location entity interface.
 */
interface OwnTracksLocationInterface extends ContentEntityInterface, EntityOwnerInterface {

  /**
   * Creates an owntracks_location entity from a request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   A content type application/json POST request.
   */
  public static function createFromRequest(Request $request);

  /**
   * Creates an owntracks_location entity from an object.
   *
   * @param \stdClass $content
   *   An object containing the required location properties.
   */
  public static function createFromObject(\stdClass $content);

  /**
   * Gets a location array.
   *
   * @return array
   *   A numeric array containing a latitude and longitude value.
   */
  public function getLocation();

}
