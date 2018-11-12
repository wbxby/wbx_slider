<?php

namespace Drupal\wbx_slider;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface SlideStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Slide revision IDs for a specific Slide.
   *
   * @param \Drupal\wbx_slider\Entity\SlideInterface $entity
   *   The Slide entity.
   *
   * @return int[]
   *   Slide revision IDs (in ascending order).
   */
  public function revisionIds(SlideInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Slide author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Slide revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\wbx_slider\Entity\SlideInterface $entity
   *   The Slide entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(SlideInterface $entity);

  /**
   * Unsets the language for all Slide with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
