<?php

namespace Drupal\wbx_slider\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Slide entities.
 */
class SlideViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}
