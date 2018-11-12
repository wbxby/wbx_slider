<?php

namespace Drupal\wbx_slider\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\wbx_slider\Entity\SlideInterface;

/**
 * Class SlideController.
 *
 *  Returns responses for Slide routes.
 */
class SlideController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Slide  revision.
   *
   * @param int $slide_revision
   *   The Slide  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($slide_revision) {
    $slide = $this->entityManager()->getStorage('slide')->loadRevision($slide_revision);
    $view_builder = $this->entityManager()->getViewBuilder('slide');

    return $view_builder->view($slide);
  }

  /**
   * Page title callback for a Slide  revision.
   *
   * @param int $slide_revision
   *   The Slide  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($slide_revision) {
    $slide = $this->entityManager()->getStorage('slide')->loadRevision($slide_revision);
    return $this->t('Revision of %title from %date', ['%title' => $slide->label(), '%date' => format_date($slide->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Slide .
   *
   * @param \Drupal\wbx_slider\Entity\SlideInterface $slide
   *   A Slide  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(SlideInterface $slide) {
    $account = $this->currentUser();
    $langcode = $slide->language()->getId();
    $langname = $slide->language()->getName();
    $languages = $slide->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $slide_storage = $this->entityManager()->getStorage('slide');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $slide->label()]) : $this->t('Revisions for %title', ['%title' => $slide->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all slide revisions") || $account->hasPermission('administer slide entities')));
    $delete_permission = (($account->hasPermission("delete all slide revisions") || $account->hasPermission('administer slide entities')));

    $rows = [];

    $vids = $slide_storage->revisionIds($slide);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\wbx_slider\SlideInterface $revision */
      $revision = $slide_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $slide->getRevisionId()) {
          $link = $this->l($date, new Url('entity.slide.revision', ['slide' => $slide->id(), 'slide_revision' => $vid]));
        }
        else {
          $link = $slide->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.slide.translation_revert', ['slide' => $slide->id(), 'slide_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.slide.revision_revert', ['slide' => $slide->id(), 'slide_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.slide.revision_delete', ['slide' => $slide->id(), 'slide_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['slide_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
