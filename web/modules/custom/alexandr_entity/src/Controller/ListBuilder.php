<?php

namespace Drupal\alexandr_entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class ListBuilder extends EntityListBuilder {
  /**
   * {@inheritdoc}
   *
   * Building the header and content lines for the feedback list.
   */

  public function buildHeader() {
    $header['name'] = $this->t('Name');
    $header['avatar'] = $this->t('Avatar');
    $header['comment'] = $this->t('Comment');
    $header['image'] = $this->t('Image');
    $header['phone'] = $this->t('Phone');
    $header['email'] = $this->t('Email');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['name'] = $entity->getName();
    $row['avatar'] = $entity->getAvatar();
    $row['comment'] = $entity->getComment();
    $row['image'] = $entity->getImage();
    $row['phone'] = $entity->getPhone();
    $row['email'] = $entity->getEmail();
    $row['date'] = date('d/m/Y G:i:s', $entity->getDate());
    return $row + parent::buildRow($entity);
  }

}
