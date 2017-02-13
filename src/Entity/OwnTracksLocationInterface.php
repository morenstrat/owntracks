<?php

namespace Drupal\owntracks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\Request;

interface OwnTracksLocationInterface extends ContentEntityInterface {

  public static function createFromRequest(Request $request);

  public function getLocation();

}
