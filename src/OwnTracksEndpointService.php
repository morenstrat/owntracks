<?php

namespace Drupal\owntracks;

use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Core\Session\AccountInterface;
use Drupal\owntracks\Entity\OwnTracksEntityInterface;
use Drupal\owntracks\Entity\OwnTracksLocation;
use Drupal\owntracks\Entity\OwnTracksTransition;
use Drupal\owntracks\Entity\OwnTracksWaypoint;

/**
 * Provides the owntracks endpoint service.
 */
class OwnTracksEndpointService {

  /**
   * \Drupal\owntracks\OwnTracksWaypointService definition.
   *
   * @var \Drupal\owntracks\OwnTracksWaypointService
   */
  protected $waypointService;

  /**
   * \Drupal\Core\Session\AccountInterface defintion.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * OwnTracksEndpointService constructor.
   *
   * @param \Drupal\owntracks\OwnTracksWaypointService $waypoint_service
   * @param \Drupal\Core\Session\AccountInterface $current_user;
   */
  public function __construct(OwnTracksWaypointService $waypoint_service, AccountInterface $current_user) {
    $this->waypointService = $waypoint_service;
    $this->currentUser = $current_user;
  }

  /**
   * Create an OwnTracks entity.
   *
   * @param $data
   *
   * @throws \Drupal\Component\Serialization\Exception\InvalidDataTypeException
   * @throws \Exception
   */
  public function create($data) {
    $json = (array) json_decode($data);

    if (!isset($json['_type'])) {
      throw new InvalidDataTypeException('Missing payload type: ' . $data);
    }

    if (isset($json['desc'])) {
      $json['description'] = $json['desc'];
      unset($json['desc']);
    }

    if ($json['_type'] === 'location') {
      if (empty($json['t'])) {
        $json['t'] = 'a';
      }

      $entity = OwnTracksLocation::create($json);
    }
    elseif ($json['_type'] === 'transition') {
      $waypoint_id = $this->waypointService->getWaypointId($this->currentUser->id(), $json['wtst']);

      if ($waypoint_id) {
        $json['waypoint'] = $waypoint_id;
      }

      $entity = OwnTracksTransition::create($json);
    }
    elseif($json['_type'] === 'waypoint') {
      if ($this->waypointService->waypointExists($this->currentUser->id(), $json)) {
        return;
      }

      $entity = OwnTracksWaypoint::create($json);
    }
    elseif ($json['_type'] === 'waypoints') {
      foreach ($json['waypoints'] as $waypoint) {
        $this->create(json_encode($waypoint));
      }

      return;
    }
    else {
      throw new InvalidDataTypeException('Invalid payload type:' . $data);
    }

    if ($entity instanceof OwnTracksEntityInterface) {
      $violations = $entity->validate();

      if ($violations->count() !== 0) {
        throw new InvalidDataTypeException('Invalid payload data: ' . $data);
      }

      $entity->save();
    }
    else {
      throw new \Exception('Internal server error:' . $data);
    }
  }

}
