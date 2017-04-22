<?php

namespace Drupal\owntracks\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for owntracks entities.
 */
class OwnTracksEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    $access = AccessResult::allowedIfHasPermission($account, $this->entityType->getAdminPermission());
    return $access->orIf(AccessResult::allowedIfHasPermission($account, 'create owntracks entities'));
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $owner = $account->id() === $entity->getOwnerId();
    $permissions = [];

    switch ($operation) {
      case "view":
        $permissions[] = 'view any owntracks entity';

        if ($owner) {
          $permissions[] = 'view own owntracks entities';
        }
        break;

      case "update":
        $permissions[] = 'edit any owntracks entity';

        if ($owner) {
          $permissions[] = 'edit own owntracks entities';
        }
        break;

      case "delete":
        $permissions[] = 'delete any owntracks entity';

        if ($owner) {
          $permissions[] = 'delete own owntracks entities';
        }
        break;
    }

    $access = AccessResult::allowedIfHasPermissions($account, $permissions, 'OR');
    return $access->orIf(AccessResult::allowedIfHasPermission($account, $this->entityType->getAdminPermission()))->addCacheableDependency($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkFieldAccess($operation, FieldDefinitionInterface $field_definition, AccountInterface $account, FieldItemListInterface $items = NULL) {
    $administrative_fields = ['uid'];
    if ($operation == 'edit' && in_array($field_definition->getName(), $administrative_fields, TRUE)) {
      return AccessResult::allowedIfHasPermission($account, $this->entityType->getAdminPermission());
    }

    return parent::checkFieldAccess($operation, $field_definition, $account, $items);
  }

}
