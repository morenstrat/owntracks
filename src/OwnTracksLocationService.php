<?php

namespace Drupal\owntracks;

use Drupal\Core\Config\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\user\UserInterface;

/**
 * Provides the owntracks location service.
 */
class OwnTracksLocationService {

  /**
   * Drupal\Core\Entity\Query\QueryFactory definition.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;


  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * OwnTracksLocationService constructor.
   *
   * @param QueryFactory $entity_query
   *   The entity query service.
   *
   * @param EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(QueryFactory $entity_query, EntityTypeManagerInterface $entity_type_manager) {
    $this->entityQuery = $entity_query;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Get a user's location records.
   *
   * @param UserInterface $user
   *
   * @return array
   *   The user's track.
   */
  public function getUserTrack(UserInterface $user) {
    $track = [];
    return $track;
  }

}