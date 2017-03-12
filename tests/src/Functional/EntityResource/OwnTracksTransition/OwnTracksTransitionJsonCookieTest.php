<?php

namespace Drupal\Tests\owntracks\Functional\EntityResource\OwnTracksTransition;

use Drupal\Tests\rest\Functional\CookieResourceTestTrait;

/**
 * Class OwnTracksTransitionJsonCookieTest.
 *
 * @group rest
 */
class OwnTracksTransitionJsonCookieTest extends OwnTracksTransitionResourceTestBase {

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
  protected static $expectedErrorMimeType = 'application/json';

  /**
   * {@inheritdoc}
   */
  protected static $auth = 'cookie';

}
