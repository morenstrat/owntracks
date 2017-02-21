<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Access\AccessResult;

/**
 * OwnTracksUserAccess definition.
 */
class OwnTracksUserAccess implements AccessInterface {

  /**
   * Check access to user owntracks locations map.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The currently logged in user.
   * @param \Drupal\user\UserInterface $user
   *   The currently visited user.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Allowed or Forbidden.
   */
  public function access(AccountInterface $account, UserInterface $user) {
    if ($account->hasPermission('view any owntracks location') || ($account->hasPermission('view own owntracks locations') && $user->id() === $account->id())) {
      return AccessResult::allowedIfHasPermission($account, 'access user profiles');
    }
    else {
      return AccessResult::forbidden();
    }
  }

}
