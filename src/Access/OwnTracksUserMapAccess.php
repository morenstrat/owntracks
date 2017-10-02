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
    $permissions = ['administer owntracks', 'view any owntracks entity'];

    if ($user->id() === $account->id()) {
      $permissions[] = 'view own owntracks entities';
    }

    return AccessResult::allowedIfHasPermission($account, 'access user profiles')
      ->andIf(AccessResult::allowedIfHasPermissions($account, $permissions, 'OR'));
  }
}
