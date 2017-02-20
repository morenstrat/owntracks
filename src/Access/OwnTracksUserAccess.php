<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
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
   * @param AccountInterface $account
   *   The currently logged in user.
   * @param RouteMatchInterface $route_match
   *   The route match service.
   *
   * @return AccessResult
   *   Allowed or Forbidden.
   */
  public function access(AccountInterface $account, RouteMatchInterface $route_match) {
    if ($account->hasPermission('view any owntracks location')) {
      return AccessResult::allowedIfHasPermission($account, 'access user profile');
    }

    if ($account->hasPermission('view own owntracks locations')) {
      $user = $route_match->getParameter('user');

      if ($user instanceof UserInterface && $user->id() === $account->id()) {
        return AccessResult::allowedIfHasPermission($account, 'access user profile');
      }
    }

    return AccessResult::forbidden();
  }

}
