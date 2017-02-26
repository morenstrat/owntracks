<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Defines the owntracks_location entity interface.
 */
interface OwnTracksLocationInterface extends ContentEntityInterface, EntityOwnerInterface {

  /**
   * Gets a location array.
   *
   * @return array
   *   A numeric array containing a latitude and longitude value.
   */
  public function getLocation();

}
