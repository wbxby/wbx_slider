<?php

namespace Drupal\wbx_slider;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\wbx_slider\Entity\SlideInterface;

/**
 * Defines the storage handler class for Slide entities.
 *
 * This extends the base storage class, adding required special handling for
 * Slide entities.
 *
 * @ingroup wbx_slider
 */
class SlideStorage extends SqlContentEntityStorage implements SlideStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(SlideInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {slide_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {slide_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(SlideInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {slide_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('slide_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
