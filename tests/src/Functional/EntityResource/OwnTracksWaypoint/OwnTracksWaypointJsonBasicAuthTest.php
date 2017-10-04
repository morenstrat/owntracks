<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksWaypoint;

use Drupal\Tests\rest\Functional\BasicAuthResourceTestTrait;

/**
 * Class OwnTracksWaypointJsonBasicAuthTest.
 *
 * @group rest
 */
class OwnTracksWaypointJsonBasicAuthTest extends OwnTracksWaypointResourceTestBase {

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
