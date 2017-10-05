<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksWaypoint;

use Drupal\owntracks\Entity\OwnTracksWaypoint;
use Drupal\Tests\rest\Functional\BcTimestampNormalizerUnixTestTrait;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;
use Drupal\user\Entity\User;

/**
 * Class OwnTracksWaypointResourceTestBase.
 */
abstract class OwnTracksWaypointResourceTestBase extends EntityResourceTestBase {

  use BcTimestampNormalizerUnixTestTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['owntracks'];

  /**
   * {@inheritdoc}
   */
  protected static $entityTypeId = 'owntracks_waypoint';

  /**
   * {@inheritdoc}
   */
  protected static $patchProtectedFieldNames = [
    'uid',
  ];

  /**
   * Drupal\owntracks\Entity\OwnTracksWaypointInterface definition.
   *
   * @var \Drupal\owntracks\Entity\OwnTracksWaypointInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  protected function setUpAuthorization($method) {
    switch ($method) {
      case 'GET':
        $this->grantPermissionsToTestedRole(['view any owntracks entity']);
        break;

      case 'POST':
        $this->grantPermissionsToTestedRole(['create owntracks entities']);
        break;

      case 'PATCH':
        $this->grantPermissionsToTestedRole(['update any owntracks entity']);
        break;

      case 'DELETE':
        $this->grantPermissionsToTestedRole(['delete any owntracks entity']);
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function createEntity() {
    $owntracks_waypoint = OwnTracksWaypoint::create([
      '_type' => 'waypoint',
      'description' => 'valid',
      'lat' => '-88.89765467',
      'lon' => '179.89765456',
      'rad' => 0,
      'tst' => 123456,
    ]);
    $owntracks_waypoint->setOwnerId(static::$auth ? $this->account->id() : 0)->save();
    return $owntracks_waypoint;
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpectedNormalizedEntity() {
    $author = User::load($this->entity->getOwnerId());
    return [
      'id' => [
        [
          'value' => 1,
        ],
      ],
      'uuid' => [
        [
          'value' => $this->entity->uuid(),
        ],
      ],
      'uid' => [
        [
          'target_id' => (int) $author->id(),
          'target_type' => 'user',
          'target_uuid' => $author->uuid(),
          'url' => base_path() . 'user/' . $author->id(),
        ],
      ],
      '_type' => [
        [
          'value' => 'waypoint',
        ],
      ],
      'description' => [
        [
          'value' => 'valid',
        ],
      ],
      'lat' => [
        [
          'value' => '-88.89765467',
        ],
      ],
      'lon' => [
        [
          'value' => '179.89765456',
        ],
      ],
      'rad' => [
        [
          'value' => 0,
        ],
      ],
      'tst' => [
        $this->formatExpectedTimestampItemValues(123456),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getNormalizedPostEntity() {
    return [
      '_type' => [
        [
          'value' => 'waypoint',
        ],
      ],
      'description' => [
        [
          'value' => 'valid',
        ],
      ],
      'lat' => [
        [
          'value' => '-88.89765467',
        ],
      ],
      'lon' => [
        [
          'value' => '179.89765456',
        ],
      ],
      'rad' => [
        [
          'value' => 0,
        ],
      ],
      'tst' => [
        [
          'value' => 123456,
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getExpectedUnauthorizedAccessMessage($method) {
    if ($this->config('rest.settings')->get('bc_entity_resource_permissions')) {
      return parent::getExpectedUnauthorizedAccessMessage($method);
    }

    switch ($method) {
      case 'GET':
        if ($this->entity->getOwnerId() === \Drupal::currentUser()->id()) {
          return "The following permissions are required: 'administer owntracks' OR 'view any owntracks entity' OR 'view own owntracks entities'.";
        }
        else {
          return "The following permissions are required: 'administer owntracks' OR 'view any owntracks entity'.";
        }

      case 'POST':
        return "The following permissions are required: 'administer owntracks' OR 'create owntracks entities'.";

      case 'PATCH':
        if ($this->entity->getOwnerId() === \Drupal::currentUser()->id()) {
          return "The following permissions are required: 'administer owntracks' OR 'update any owntracks entity' OR 'update own owntracks entities'.";
        }
        else {
          return "The following permissions are required: 'administer owntracks' OR 'update any owntracks entity'.";
        }

      case 'DELETE':
        if ($this->entity->getOwnerId() === \Drupal::currentUser()->id()) {
          return "The following permissions are required: 'administer owntracks' OR 'delete any owntracks entity' OR 'delete own owntracks entities'.";
        }
        else {
          return "The following permissions are required: 'administer owntracks' OR 'delete any owntracks entity'.";
        }

    }

    return parent::getExpectedUnauthorizedAccessMessage($method);
  }

}
