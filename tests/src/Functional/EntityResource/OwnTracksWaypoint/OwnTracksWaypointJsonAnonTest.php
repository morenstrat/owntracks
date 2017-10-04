<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksWaypoint;

use Drupal\Tests\rest\Functional\AnonResourceTestTrait;

/**
 * Class OwnTracksWaypointJsonAnonTest.
 *
 * @group rest
 */
class OwnTracksWaypointJsonAnonTest extends OwnTracksWaypointResourceTestBase {

  use AnonResourceTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $format = 'json';

  /**
   * {@inheritdoc}
   */
  protected static $mimeType = 'application/json';

}
