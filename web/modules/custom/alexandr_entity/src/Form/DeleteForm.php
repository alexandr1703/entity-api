<?php

namespace Drupal\alexandr_entity\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a form for deleting a content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class DeleteForm extends ContentEntityConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete comment ?');
  }

  /**
   * {@inheritdoc}
   *
   * If the delete command is canceled, return to the contact list.
   */
  public function getCancelUrl() {
    return new Url('alexandr.entity');
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   *
   * Delete the entity and log the event. logger() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $this->logger('alexandr_entity')->notice('@type: deleted %title.',
      [
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ]);
    $form_state->setRedirect('alexandr.entity');
  }

}
