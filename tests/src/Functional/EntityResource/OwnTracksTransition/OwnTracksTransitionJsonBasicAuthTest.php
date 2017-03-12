<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\Tests\rest\Functional\BasicAuthResourceTestTrait;
use Drupal\Tests\rest\Functional\JsonBasicAuthWorkaroundFor2805281Trait;

/**
 * Class OwnTracksTransitionJsonBasicAuthTest.
 *
 * @group rest
 */
class OwnTracksTransitionJsonBasicAuthTest extends OwnTracksTransitionResourceTestBase {

  use BasicAuthResourceTestTrait;

  /**
   * {@inheritdoc}
   */
  public static $modules = ['basic_auth'];

  /**
   * {@inheritdoc}
   */
  protected static $format = 'json';

  /**
   * {@inheritdoc}
   */
  protected static $mimeType = 'application/json';

  /**
   * {@inheritdoc}
   */
  protected static $expectedErrorMimeType = 'application/json';

  /**
   * {@inheritdoc}
   */
  protected static $auth = 'basic_auth';

  // @todo Fix in https://www.drupal.org/node/2805281: remove this trait usage.
  use JsonBasicAuthWorkaroundFor2805281Trait {
    JsonBasicAuthWorkaroundFor2805281Trait::assertResponseWhenMissingAuthentication insteadof BasicAuthResourceTestTrait;
  }

}
