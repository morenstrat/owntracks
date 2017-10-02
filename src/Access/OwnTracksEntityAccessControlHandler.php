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
    $permissions = [$this->entityType->getAdminPermission(), 'create owntracks entities'];
    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $permissions = [$this->entityType->getAdminPermission(), $operation . ' any owntracks entity'];

    if ($account->id() === $entity->getOwnerId()) {
      $permissions[] = $operation . ' own owntracks entities';
    }

    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR')
      ->addCacheableDependency($entity);
  }

  /**
   * {@inheritdoc}
   */
  protected function checkFieldAccess($operation, FieldDefinitionInterface $field_definition, AccountInterface $account, FieldItemListInterface $items = NULL) {
    if ($operation === 'edit' && $field_definition->getName() === 'uid') {
      return AccessResult::allowedIfHasPermission($account, $this->entityType->getAdminPermission());
    }

    return parent::checkFieldAccess($operation, $field_definition, $account, $items);
  }

}
