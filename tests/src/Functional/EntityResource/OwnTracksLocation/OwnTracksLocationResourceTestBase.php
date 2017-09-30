<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksLocation;

use Drupal\owntracks\Entity\OwnTracksLocation;
use Drupal\Tests\rest\Functional\BcTimestampNormalizerUnixTestTrait;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;
use Drupal\user\Entity\User;

/**
 * Class OwnTracksLocationResourceTestBase.
 */
abstract class OwnTracksLocationResourceTestBase extends EntityResourceTestBase {

  use BcTimestampNormalizerUnixTestTrait;

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
        $this->grantPermissionsToTestedRole(['view any owntracks entity']);
        break;

      case 'POST':
        $this->grantPermissionsToTestedRole(['create owntracks entities']);
        break;

      case 'PATCH':
        $this->grantPermissionsToTestedRole(['edit any owntracks entity']);
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
    $owntracks_location = OwnTracksLocation::create([
      '_type' => 'location',
      'acc' => 0,
      'alt' => 0,
      'batt' => 100,
      'cog' => 360,
      'description' => 'valid',
      'event' => 'enter',
      'lat' => '-88.89765467',
      'lon' => '179.89765456',
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
          'target_id' => (int) $author->id(),
          'target_type' => 'user',
          'target_uuid' => $author->uuid(),
          'url' => base_path() . 'user/' . $author->id(),
        ],
      ],
      '_type' => [
        [
          'value' => 'location',
        ]
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
        $this->formatExpectedTimestampItemValues(123456),
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
      '_type' => [
        [
          'value' => 'location',
        ]
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
        $this->formatExpectedTimestampItemValues(123456),
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
  protected function getExpectedUnauthorizedAccessMessage($method) {
    if ($this->config('rest.settings')->get('bc_entity_resource_permissions')) {
      return parent::getExpectedUnauthorizedAccessMessage($method);
    }

    switch ($method) {
      case 'GET':
        return "The 'administer owntracks' permission is required.";

      case 'POST':
        return "The 'create owntracks entities' permission is required.";

      case 'PATCH':
        return "The 'administer owntracks' permission is required.";

      case 'DELETE':
        return "The 'administer owntracks' permission is required.";
    }

    return parent::getExpectedUnauthorizedAccessMessage($method);
  }

}
