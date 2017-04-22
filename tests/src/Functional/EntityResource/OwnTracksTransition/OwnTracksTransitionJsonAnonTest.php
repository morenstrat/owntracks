<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\Tests\rest\Functional\AnonResourceTestTrait;

/**
 * Class OwnTracksTransitionJsonAnonTest.
 *
 * @group rest
 */
class OwnTracksTransitionJsonAnonTest extends OwnTracksTransitionResourceTestBase {

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
