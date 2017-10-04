<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksWaypoint;

use Drupal\Tests\rest\Functional\CookieResourceTestTrait;

/**
 * Class OwnTracksWaypointJsonCookieTest.
 *
 * @group rest
 */
class OwnTracksWaypointJsonCookieTest extends OwnTracksWaypointResourceTestBase {

  use CookieResourceTestTrait;

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
  protected static $auth = 'cookie';

}
