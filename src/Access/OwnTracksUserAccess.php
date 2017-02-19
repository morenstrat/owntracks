<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\UserInterface;

/**
 * OwnTracksUserAccess definition.
 */
class OwnTracksUserAccess implements AccessInterface {

  /**
   * Check access to user owntracks locations map.
   *
   * @param AccountInterface $account
   *   The currently logged in user.
   * @param UserInterface $user
   *   The owner of the owntracks location map.
   *
   * @return bool
   *   TRUE if access should be granted, FALSE otherwise.
   */
  public function access(AccountInterface $account, UserInterface $user) {
    if ($account->hasPermission('view any owntracks location')) {
      return TRUE;
    }

    if ($account->hasPermission('view own owntracks locations') && $account->id() === $user->id()) {
      return TRUE;
    }

    return FALSE;
  }

}
