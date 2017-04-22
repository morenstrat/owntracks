<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\Tests\rest\Functional\BasicAuthResourceTestTrait;

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
  protected static $auth = 'basic_auth';

}
