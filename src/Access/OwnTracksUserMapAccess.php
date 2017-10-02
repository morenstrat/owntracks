<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Access\AccessResult;

/**
 * OwnTracksUserMapAccess definition.
 */
class OwnTracksUserMapAccess implements AccessInterface {

  /**
   * Check access to owntracks user map.
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
    if ($account->hasPermission('access user profiles')) {
      if ($account->hasPermission('view any owntracks entity')) {
        return AccessResult::allowed();
      }

      if ($user->id() === $account->id() && $account->hasPermission('view own owntracks entities')) {
        return AccessResult::allowed();
      }
    }

    return AccessResult::forbidden();
  }

}
