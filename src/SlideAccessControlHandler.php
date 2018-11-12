<?php

namespace Drupal\wbx_slider;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Slide entity.
 *
 * @see \Drupal\wbx_slider\Entity\Slide.
 */
class SlideAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\wbx_slider\Entity\SlideInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished slide entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published slide entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit slide entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete slide entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add slide entities');
  }

}
