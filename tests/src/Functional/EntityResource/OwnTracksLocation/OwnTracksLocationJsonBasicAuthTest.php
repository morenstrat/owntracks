<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksLocation;

use Drupal\Tests\rest\Functional\BasicAuthResourceTestTrait;

/**
 * Class OwnTracksLocationJsonBasicAuthTest.
 *
 * @group rest
 */
class OwnTracksLocationJsonBasicAuthTest extends OwnTracksLocationResourceTestBase {

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
