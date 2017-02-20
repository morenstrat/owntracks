<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\CurrentRouteMatch;
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
   * @param CurrentRouteMatch $current_route_match
   *   The current route match service.
   *
   * @return bool
   *   TRUE if access should be granted, FALSE otherwise.
   */
  public function access(AccountInterface $account, CurrentRouteMatch $current_route_match) {
    if ($account->hasPermission('view any owntracks location')) {
      return TRUE;
    }

    if ($account->hasPermission('view own owntracks locations')) {
      $user = $current_route_match->getParameter('user');

      if ($user instanceof UserInterface && $user->id() === $account->id()) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
