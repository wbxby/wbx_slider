<?php

namespace Drupal\wbx_slider\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Slide revision.
 *
 * @ingroup wbx_slider
 */
class SlideRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Slide revision.
   *
   * @var \Drupal\wbx_slider\Entity\SlideInterface
   */
  protected $revision;

  /**
   * The Slide storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $SlideStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new SlideRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->SlideStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('slide'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'slide_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', ['%revision-date' => format_date($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.slide.version_history', ['slide' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $slide_revision = NULL) {
    $this->revision = $this->SlideStorage->loadRevision($slide_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->SlideStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Slide: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    drupal_set_message(t('Revision from %revision-date of Slide %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.slide.canonical',
       ['slide' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {slide_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.slide.version_history',
         ['slide' => $this->revision->id()]
      );
    }
  }

}
