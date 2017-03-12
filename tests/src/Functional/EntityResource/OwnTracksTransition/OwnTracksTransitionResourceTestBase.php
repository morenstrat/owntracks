<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\owntracks\Entity\OwnTracksTransition;
use Drupal\Tests\rest\Functional\EntityResource\EntityResourceTestBase;
use Drupal\user\Entity\User;

/**
 * Class OwnTracksTransitionResourceTestBase.
 */
abstract class OwnTracksTransitionResourceTestBase extends EntityResourceTestBase {

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
    $owntracks_transition = OwnTracksTransition::create([
      'wtst' => 23456,
      'acc' => 0,
      'description' => 'valid',
      'event' => 'enter',
      'lat' => -90,
      'lon' => 180,
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
          'target_id' => $author->id(),
          'target_type' => 'user',
          'target_uuid' => $author->uuid(),
          'url' => base_path() . 'user/' . $author->id(),
        ],
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
          'value' => -90,
        ],
      ],
      'lon' => [
        [
          'value' => 180,
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
  protected function getNormalizedPostEntity() {
    return [
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
          'value' => -90,
        ],
      ],
      'lon' => [
        [
          'value' => 180,
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

}
