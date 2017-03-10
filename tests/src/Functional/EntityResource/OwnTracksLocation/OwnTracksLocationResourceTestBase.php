<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksLocation;

use Drupal\owntracks\Entity\OwnTracksLocation;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;
use Drupal\user\Entity\User;

/**
 * Class OwnTracksLocationResourceTestBase.
 */
abstract class OwnTracksLocationResourceTestBase extends EntityResourceTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['owntracks'];

  /**
   * {@inheritdoc}
   */
  protected static $entityTypeId = 'owntracks_location';

  /**
   * {@inheritdoc}
   */
  protected static $patchProtectedFieldNames = [
    'uid',
  ];

  /**
   * Drupal\owntracks\Entity\OwnTracksLocationInterface definition.
   *
   * @var \Drupal\owntracks\Entity\OwnTracksLocationInterface
   */
  protected $entity;

  /**
   * {@inheritdoc}
   */
  protected function setUpAuthorization($method) {
    switch ($method) {
      case 'GET':
        $this->grantPermissionsToTestedRole(['view any owntracks location']);
        break;

      case 'POST':
        $this->grantPermissionsToTestedRole(['create owntracks locations']);
        break;

      case 'PATCH':
        $this->grantPermissionsToTestedRole(['edit any owntracks location']);
        break;

      case 'DELETE':
        $this->grantPermissionsToTestedRole(['delete any owntracks location']);
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function createEntity() {
    $owntracks_location = OwnTracksLocation::create([
      'acc' => 0,
      'alt' => 0,
      'batt' => 100,
      'cog' => 360,
      'description' => 'valid',
      'event' => 'enter',
      'lat' => -90,
      'lon' => 180,
      'rad' => 0,
      't' => 'u',
      'tid' => 'bo',
      'tst' => 123456,
      'vac' => 0,
      'vel' => 0,
      'p' => 0,
      'con' => 'm',
    ]);
    $owntracks_location->setOwnerId(static::$auth ? $this->account->id() : 0)->save();
    return $owntracks_location;
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
          'target_id' => $author->id(),
          'target_type' => 'user',
          'target_uuid' => $author->uuid(),
          'url' => base_path() . 'user/' . $author->id(),
        ],
      ],
      'acc' => [
        [
          'value' => 0,
        ],
      ],
      'alt' => [
        [
          'value' => 0,
        ],
      ],
      'batt' => [
        [
          'value' => 100,
        ],
      ],
      'cog' => [
        [
          'value' => 360,
        ],
      ],
      'description' => [
        [
          'value' => 'valid',
        ],
      ],
      'event' => [
        [
          'value' => 'enter',
        ],
      ],
      'lat' => [
        [
          'value' => -90,
        ],
      ],
      'lon' => [
        [
          'value' => 180,
        ],
      ],
      'rad' => [
        [
          'value' => 0,
        ],
      ],
      't' => [
        [
          'value' => 'u',
        ],
      ],
      'tid' => [
        [
          'value' => 'bo',
        ],
      ],
      'tst' => [
        [
          'value' => 123456,
        ],
      ],
      'vac' => [
        [
          'value' => 0,
        ],
      ],
      'vel' => [
        [
          'value' => 0,
        ],
      ],
      'p' => [
        [
          'value' => 0,
        ],
      ],
      'con' => [
        [
          'value' => 'm',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getNormalizedPostEntity() {
    return [
      'acc' => [
        [
          'value' => 0,
        ],
      ],
      'alt' => [
        [
          'value' => 0,
        ],
      ],
      'batt' => [
        [
          'value' => 100,
        ],
      ],
      'cog' => [
        [
          'value' => 360,
        ],
      ],
      'description' => [
        [
          'value' => 'valid',
        ],
      ],
      'event' => [
        [
          'value' => 'enter',
        ],
      ],
      'lat' => [
        [
          'value' => -90,
        ],
      ],
      'lon' => [
        [
          'value' => 180,
        ],
      ],
      'rad' => [
        [
          'value' => 0,
        ],
      ],
      't' => [
        [
          'value' => 'u',
        ],
      ],
      'tid' => [
        [
          'value' => 'bo',
        ],
      ],
      'tst' => [
        [
          'value' => 123456,
        ],
      ],
      'vac' => [
        [
          'value' => 0,
        ],
      ],
      'vel' => [
        [
          'value' => 0,
        ],
      ],
      'p' => [
        [
          'value' => 0,
        ],
      ],
      'con' => [
        [
          'value' => 'm',
        ],
      ],
    ];
  }

}
