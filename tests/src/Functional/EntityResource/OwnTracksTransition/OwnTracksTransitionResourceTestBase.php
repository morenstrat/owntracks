<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\owntracks\Entity\OwnTracksTransition;
use Drupal\Tests\rest\Functional\BcTimestampNormalizerUnixTestTrait;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;
use Drupal\user\Entity\User;

/**
 * Class OwnTracksTransitionResourceTestBase.
 */
abstract class OwnTracksTransitionResourceTestBase extends EntityResourceTestBase {

  use BcTimestampNormalizerUnixTestTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['owntracks'];

  /**
   * {@inheritdoc}
   */
  protected static $entityTypeId = 'owntracks_transition';

  /**
   * {@inheritdoc}
   */
  protected static $patchProtectedFieldNames = [
    'uid',
  ];

  /**
   * Drupal\owntracks\Entity\OwnTracksTransitionInterface definition.
   *
   * @var \Drupal\owntracks\Entity\OwnTracksTransitionInterface
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
    $owntracks_transition = OwnTracksTransition::create([
      '_type' => 'transition',
      'wtst' => 23456,
      'acc' => 0,
      'description' => 'valid',
      'event' => 'enter',
      'lat' => '-88.89765467',
      'lon' => '179.89765456',
      't' => 'c',
      'tid' => 'bo',
      'tst' => 123456,
    ]);
    $owntracks_transition->setOwnerId(static::$auth ? $this->account->id() : 0)->save();
    return $owntracks_transition;
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
          'value' => 'transition',
        ]
      ],
      'wtst' => [
        $this->formatExpectedTimestampItemValues(23456),
      ],
      'acc' => [
        [
          'value' => 0,
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
      't' => [
        [
          'value' => 'c',
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
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function getNormalizedPostEntity() {
    return [
      '_type' => [
        [
          'value' => 'transition',
        ]
      ],
      'wtst' => [
        [
          'value' => 23456,
        ],
      ],
      'acc' => [
        [
          'value' => 0,
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
      't' => [
        [
          'value' => 'c',
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
        return "The following permissions are required: 'administer owntracks' OR 'view any owntracks entity'.";

      case 'POST':
        return "The following permissions are required: 'administer owntracks' OR 'create owntracks entities'.";

      case 'PATCH':
        return "The following permissions are required: 'administer owntracks' OR 'update any owntracks entity' OR 'update own owntracks entities'.";

      case 'DELETE':
        return "The following permissions are required: 'administer owntracks' OR 'delete any owntracks entity' OR 'delete own owntracks entities'.";
    }

    return parent::getExpectedUnauthorizedAccessMessage($method);
  }

}
