<?php

namespace Drupal\alexandr_entity\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Provides a Guest entity form.
 */
class GuestForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $form['messages-name'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['form-message'],
      ],
    ];

    $form['name']['widget'][0]['value']['#ajax'] = [
      'callback' => '::setMessageName',
      'event' => 'change',
    ];
    $form['messages-comment'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['form-message-comment'],
      ],
    ];
    $form['comment']['widget'][0]['value']['#ajax'] = [
      'callback' => '::setMessageComment',
      'event' => 'change',
    ];
    $form['messages-phone'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['form-message-phone'],
      ],
    ];
    $form['phone']['widget'][0]['value']['#ajax'] = [
      'callback' => '::setMessagePhone',
      'event' => 'change',
    ];
    $form['messages-email'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['form-message-email'],
      ],
    ];
    $form['email']['widget'][0]['value']['#ajax'] = [
      'callback' => '::setMessageEmail',
      'event' => 'change',
    ];
    return $form;
  }

  /**
   * AJAX validation for name.
   */
  public function setMessageName(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $name = $form['name']['widget'][0]['value']['#value'];
    if (strlen($name) <= 1) {
      $response->addCommand(
        new HtmlCommand(
          '.form-message',
          '<div class="my-message-error">' . $this->t('Enter correct name.')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.form-message',
          '<div class="my-message">' . $this->t('Name: Ok!')
        )
      );
    }
    return $response;
  }

  /**
   * AJAX validation for comment.
   */
  public function setMessageComment(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $comment = $form['comment']['widget'][0]['value']['#value'];
    if (strlen($comment) <= 1) {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-comment',
          '<div class="my-message-error">' . $this->t('Enter longer comment, be more creative)')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-comment',
          '<div class="my-message">' . $this->t('Good comment!')
        )
      );
    }
    return $response;
  }

  /**
   * AJAX validation for email.
   */
  public function setMessageEmail(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $email = $form['email']['widget'][0]['value']['#value'];
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[A-Za-z-_]+[@]+[a-z]{2,12}+[.]+[a-z]{2,7}+$/', $email)) {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-email',
          '<div class="my-message-error">' . $this->t('Enter correct email in format: example@gmail.com.')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-email',
          '<div class="my-message">' . $this->t('Email: Ok!')
        )
      );
    }
    return $response;
  }

  /**
   * AJAX validation for phone.
   */
  public function setMessagePhone(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $phone = $form['phone']['widget'][0]['value']['#value'];
    if ((!preg_match('/^[0-9]{10}$/', $phone))) {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-phone',
          '<div class="my-message-error">' . $this->t('Enter correct phone number in format: 1234567890')
        )
      );
    }
    else {
      $response->addCommand(
        new HtmlCommand(
          '.form-message-phone',
          '<div class="my-message">' . $this->t('Phone: Ok!')
        )
      );
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);

    $entity = $this->getEntity();
    $entity_type = $entity->getEntityType();

    $arguments = [
      '@entity_type' => $entity_type->getSingularLabel(),
      '%entity' => $entity->label(),
      'link' => $entity->toLink($this->t('View'), 'canonical')->toString(),
    ];

    $this->logger($entity->getEntityTypeId())->notice('The @entity_type %entity has been saved.', $arguments);
    $this->messenger()->addStatus($this->t('The @entity_type %entity has been saved.', $arguments));
    $form_state->setRedirectUrl(Url::fromRoute('alexandr.entity'));
  }

}
