<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksLocation;

use Drupal\Tests\rest\Functional\AnonResourceTestTrait;

/**
 * Class OwnTracksLocationJsonAnonTest.
 *
 * @group rest
 */
class OwnTracksLocationJsonAnonTest extends OwnTracksLocationResourceTestBase {

  use AnonResourceTestTrait;

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

}
