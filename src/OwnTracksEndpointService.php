<?php

namespace Drupal\owntracks;

use Drupal\Component\Serialization\Exception\InvalidDataTypeException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\owntracks\Entity\OwnTracksEntityInterface;

/**
 * Provides the owntracks endpoint service.
 */
class OwnTracksEndpointService {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface $currentUser
   */
  protected $currentUser;

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The owntracks waypoint service.
   *
   * @var \Drupal\owntracks\OwnTracksWaypointService $waypointService
   */
  protected $waypointService;

  /**
   * Post data from the controller.
   *
   * @var string $data
   */
  protected $data;

  /**
   * Post data converted to array.
   *
   * @var array $json
   */
  protected $json;

  /**
   * The owntracks entity.
   *
   * @var \Drupal\Owntracks\Entity\OwnTracksEntityInterface $entity
   */
  protected $entity;

  /**
   * OwnTracksEndpointService constructor.
   *
   * @param \Drupal\Core\Session\AccountInterface $current_user
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface
   * @param \Drupal\owntracks\OwnTracksWaypointService $waypoint_service
   */
  public function __construct(AccountInterface $current_user, EntityTypeManagerInterface $entity_type_manager, OwnTracksWaypointService $waypoint_service) {
    $this->currentUser = $current_user;
    $this->entityTypeManager = $entity_type_manager;
    $this->waypointService = $waypoint_service;
  }

  /**
   * Creates an OwnTracks entity.
   *
   * @param string $data
   *
   * @throws \Drupal\Component\Serialization\Exception\InvalidDataTypeException
   */
  public function create($data) {
    $this->data = $data;
    $this->json = (array) json_decode($this->data);

    if (!isset($this->json['_type'])) {
      throw new InvalidDataTypeException('Missing payload type: ' . $this->data);
    }

    if ($this->json['_type'] === 'waypoints') {
      foreach ($this->json['waypoints'] as $waypoint) {
        $this->create(json_encode($waypoint));
      }
    }

    if (isset($this->json['desc'])) {
      $this->json['description'] = $this->json['desc'];
      unset($this->json['desc']);
    }

    switch ($this->json['_type']) {
      case 'location':
        $this->createLocation();
        break;

      case 'waypoint':
        $this->createWaypoint();
        break;

      case 'transition':
        $this->createTransition();
        break;

      default:
        throw new InvalidDataTypeException('Invalid payload type:' . $this->data);
        break;
    }
  }

  /**
   * Creates a location entity.
   */
  protected function createLocation() {
    if (empty($this->json['t'])) {
      $this->json['t'] = 'a';
    }

    $this->createEntity('owntracks_location')->saveEntity();
  }

  /**
   * Create a waypoint entity.
   */
  protected function createWaypoint() {
    $waypoint_id = $this->waypointService
      ->getWaypointId($this->currentUser->id(), $this->json['tst']);

    if (!empty($waypoint_id)) {
      $this->updateEntity('owntracks_waypoint', $waypoint_id)
        ->saveEntity();
    }
    else {
      $this->createEntity('owntracks_waypoint')->saveEntity();
    }
  }

  /**
   * Create a transition entity.
   */
  protected function createTransition() {
    $waypoint_id = $this->waypointService
      ->getWaypointId($this->currentUser->id(), $this->json['wtst']);

    if (!empty($waypoint_id)) {
      $this->json['waypoint'] = $waypoint_id;
    }

    $this->createEntity('owntracks_transition')->saveEntity();
  }

  /**
   * Create an owntracks entity.
   *
   * @param $entity_type
   *
   * @return $this
   */
  protected function createEntity($entity_type) {
    $this->entity = $this->entityTypeManager
      ->getStorage($entity_type)
      ->create($this->json);

    return $this;
  }

  /**
   * Update an owntracks entity.
   *
   * @param $entity_type
   * @param $entity_id
   *
   * @return $this
   */
  protected function updateEntity($entity_type, $entity_id) {
    $this->entity = $this->entityTypeManager
      ->getStorage($entity_type)
      ->load($entity_id);

    foreach ($this->json as $key => $value) {
      $this->entity->set($key, $value);
    }

    return $this;
  }

  /**
   * Saves an owntracks entity.
   *
   * @throws \Drupal\Component\Serialization\Exception\InvalidDataTypeException
   * @throws \Exception
   */
  protected function saveEntity() {
    if ($this->entity instanceof OwnTracksEntityInterface) {
      $violations = $this->entity->validate();

      if ($violations->count() !== 0) {
        throw new InvalidDataTypeException('Invalid payload data: ' . $this->data);
      }

      $this->entity->save();
    }
    else {
      throw new \Exception('Internal server error:' . $this->data);
    }
  }

}
