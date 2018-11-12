<?php

namespace Drupal\wbx_slider\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Slide entities.
 *
 * @ingroup wbx_slider
 */
interface SlideInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Slide name.
   *
   * @return string
   *   Name of the Slide.
   */
  public function getName();

  /**
   * Sets the Slide name.
   *
   * @param string $name
   *   The Slide name.
   *
   * @return \Drupal\wbx_slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setName($name);

  /**
   * Gets the Slide creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Slide.
   */
  public function getCreatedTime();

  /**
   * Sets the Slide creation timestamp.
   *
   * @param int $timestamp
   *   The Slide creation timestamp.
   *
   * @return \Drupal\wbx_slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Slide published status indicator.
   *
   * Unpublished Slide are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Slide is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Slide.
   *
   * @param bool $published
   *   TRUE to set this Slide to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\wbx_slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setPublished($published);

  /**
   * Gets the Slide revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Slide revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\wbx_slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Slide revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Slide revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\wbx_slider\Entity\SlideInterface
   *   The called Slide entity.
   */
  public function setRevisionUserId($uid);

}
