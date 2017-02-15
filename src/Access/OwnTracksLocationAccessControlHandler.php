<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the owntracks location entity type.
 */
class OwnTracksLocationAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    $access = AccessResult::allowedIfHasPermission($account, 'create owntracks locations');
    return $access->orIf(parent::checkCreateAccess($account, $context, $entity_bundle));
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $owner = $account->id() === $entity->getOwnerId();
    $access = parent::checkAccess($entity, $operation, $account);
    $permissions = [];

    switch ($operation) {
      case "view":
        $permissions[] = 'view any owntracks location';

        if ($owner) {
          $permissions[] = 'view own owntracks locations';
        }
        break;

      case "update":
        $permissions[] = 'edit any owntracks location';

        if ($owner) {
          $permissions[] = 'edit own owntracks locations';
        }
        break;

      case "delete":
        $permissions[] = 'delete any owntracks location';

        if ($owner) {
          $permissions[] = 'delete own owntracks locations';
        }
        break;
    }

    return $access->orIf(AccessResult::allowedIfHasPermissions($account, $permissions, 'OR'))->addCacheableDependency($entity);
  }

}
