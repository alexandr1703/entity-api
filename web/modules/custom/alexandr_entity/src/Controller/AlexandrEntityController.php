<?php

namespace Drupal\alexandr_entity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\file\Entity\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides route responses for the alexandr_entity module.
 */
class AlexandrEntityController extends ControllerBase {
  /**
   * Form build interface.
   */

  protected $formBuilder;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->formBuilder = $container->get('entity.form_builder');
    return $instance;
  }

  /**
   * Build Form.
   */
  public function build() {

    $entity = $this->entityTypeManager()->getStorage('guestbook')->create([
      'entity_type' => 'node',
      'entity_id' => 'guestbook',
    ]);
    $form = \Drupal::service('entity.form_builder')->getForm($entity, 'add');
    return $form;
  }

  /**
   * Get all records for page.
   *
   * @return array
   *   A simple array.
   */
  public function load() {
    $query = Database::getConnection()->select('alexandr_entity', 'a');
    $query->fields('a', [
      'image__target_id',
      'avatar__target_id',
      'name',
      'created',
      'email',
      'phone',
      'id',
      'comment',
    ]);
    $result = $query->execute()->fetchAll();
    return $result;
  }

  /**
   * Render all reviews entries.
   */
  public function report() {
    $info = json_decode(json_encode($this->load()), TRUE);
    $info = array_reverse($info);
    $form = $this->build();
    $rows = [];
    foreach ($info as &$value) {
      $fid = $value['image__target_id'];
      if (isset($fid)) {
        $file = File::load($fid);
        $value['image'] = !empty($file) ? file_url_transform_relative(file_create_url($file->getFileUri())) : '';
      }
      else {
        $value['image'] = '';
      }
      $avafid = $value['avatar__target_id'];
      if (isset($avafid)) {
        $avafile = File::load($avafid);
        $value['avatar'] = !empty($avafile) ? file_url_transform_relative(file_create_url($avafile->getFileUri())) : '';
      }
      else {
        $value['avatar'] = '';
      }
      $value['comment'] = [
        '#markup' => $value['comment'],
      ];
      $value['comment'] = \Drupal::service('renderer')->render($value['comment']);
      $time = time();
      $value['created'] = date('d/m/Y G:i:s', $time);
      array_push($rows, $value);
    }
    return [
      '#theme' => 'Entity_template',
      '#form' => $form,
      '#items' => $rows,
    ];
  }

}
