<?php

namespace Drupal\owntracks;

use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Provides the owntracks waypoint service.
 */
class OwnTracksWaypointService {

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * OwnTracksWaypointService constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Check if a waypoint for the given account already exists.
   *
   * @param int $uid
   * @param array $json
   *
   * @return bool
   */
  public function waypointExists(int $uid, array $json) {
    $result = $this->entityTypeManager
      ->getStorage('owntracks_waypoint')
      ->getQuery()
      ->condition('uid', $uid)
      ->condition('description', $json['description'])
      ->condition('lat', $json['lat'])
      ->condition('lon', $json['lon'])
      ->condition('rad', $json['rad'])
      ->condition('tst', $json['tst'])
      ->execute();

    return empty($result) ? FALSE : TRUE;
  }

}
