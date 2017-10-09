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
   *   Allowed or Neutral.
   */
  public function access(AccountInterface $account, UserInterface $user) {
    $access = AccessResult::allowedIfHasPermissions($account, ['administer owntracks', ' view any owntracks entity'], 'OR');

    if (!$access->isAllowed() && $account->id() === $user->id()) {
      return $access->orIf(AccessResult::allowedIfHasPermission($account, 'view own owntracks entities')
        ->cachePerUser());
    }

    return $access;
  }

}
