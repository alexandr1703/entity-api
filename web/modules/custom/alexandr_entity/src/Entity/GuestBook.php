<?php

namespace Drupal\alexandr_entity\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\file\Entity\File;
use Drupal\file\FileInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\user\EntityOwnerTrait;

/**
 * @ContentEntityType(
 *   id = "guestbook",
 *   label = @Translation("GuestBook"),
 *   base_table = "alexandr_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "owner" = "author",
 *     "published" = "published",
 *   },
 *  handlers = {
 *    "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\alexandr_entity\Form\GuestForm",
 *       "default" = "Drupal\alexandr_entity\Form\GuestForm",
 *       "edit" = "Drupal\alexandr_entity\Form\GuestForm",
 *       "delete" = "Drupal\alexandr_entity\Form\DeleteForm",
 *    },
 *   "list_builder" = "Drupal\alexandr_entity\Controller\ListBuilder",
 *    "permission_provider" = "Drupal\Core\Entity\EntityPermissionProvider",
 *    "route_provider" = {
 *       "default" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *     "views_data" = "Drupal\views\EntityViewsData",
 *   },
 *   links = {
 *     "canonical" = "/guestbook/{guestbook}",
 *     "add-form" = "/content/guest-book/add",
 *     "edit-form" = "/admin/content/guestbook/manage/{guestbook}",
 *     "delete-form" = "/admin/content/guestbook/manage/{guestbook}/delete",
 *     "collection" = "/admin/structure/guest-book",
 *   },
 *   admin_permission = "administer event",
 * )
 */
class GuestBook extends ContentEntityBase implements EntityOwnerInterface, EntityPublishedInterface {

  use EntityOwnerTrait, EntityPublishedTrait, EntityChangedTrait;

  /**
   * Creating fields.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->addConstraint('NameV')
      ->setSettings([
        'max_length' => 32,
      ])
      ->setDisplayOptions('form', [
        'weight' => 20,
        'label' => 'inline',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 1,
      ]);

    $fields['avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Avatar'))
      ->setRequired(FALSE)
      ->setSettings([
        'alt_field' => FALSE,
        'alt_field_required' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => 5242880,
      ])
      ->setDisplayOptions('form', [
        'weight' => 30,
        'label' => 'inline',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 2,
      ]);

    $fields['comment'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Comment'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'weight' => 40,
        'height' => 100,
        'label' => 'inline',
        'type' => 'text_textarea',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 3,
      ]);

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setRequired(FALSE)
      ->setSettings([
        'file_directory' => 'public://alexandr_entity/img/',
        'alt_field_required' => FALSE,
        'alt_field' => FALSE,
        'file_extensions' => 'png jpg jpeg',
        'max_filesize' => 5242880,
      ])
      ->setDisplayOptions('form', [
        'label' => 'above',
        'weight' => 50,
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 4,
      ]);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone'))
      ->setRequired(TRUE)
      ->addConstraint('PhoneV')
      ->setSettings([
        'max_length' => 10,
      ])
      ->setDescription(t('Format:1234567890'))
      ->setDisplayOptions('form', [
        'weight' => 60,
        'label' => 'inline',
        'type' => 'telephone_default',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 5,
      ]);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->addConstraint('EmailV')
      ->setDescription(t('Format:example@gmail.com'))
      ->setDisplayOptions('form', [
        'weight' => 70,
        'label' => 'inline',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'default',
        'weight' => 6,
      ]);

    $fields['created'] = BaseFieldDefinition::create('datetime')
      ->setLabel(t('Created'))
      ->setRequired(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'inline',
        'weight' => 7,
        'type' => 'timestamp',
        'settings' => [
          'date_format' => 'custom',
          'custom_date_format' => 'm/j/Y H:i:s',
        ],
      ]);

    $fields += static::ownerBaseFieldDefinitions($entity_type);
    $fields += static::publishedBaseFieldDefinitions($entity_type);

    return $fields;
  }

  /**
   * Return Name from field.
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * Return avatar from field.
   */
  public function getAvatar() {
    $avatar = [];
    $avatarfid = $this->get('avatar')->target_id;
    if (isset($avatarfid)) {
      $avatarfile = File::load($avatarfid);
      if ($avatarfile instanceof FileInterface) {
        $avatar = [
          '#type' => 'image',
          '#theme' => 'image_style',
          '#style_name' => 'thumbnail',
          '#uri' => $avatarfile->getFileUri(),
          '#width' => 100,
        ];
        $renderer = \Drupal::service('renderer');
        $avatar = $renderer->render($avatar);
      }
    }
    else {
      $avatar = '{no avatar}';
    }
    return $avatar;
  }

  /**
   * Return comment from field.
   */
  public function getComment() {
    return $this->get('comment')->value;
  }

  /**
   * Return image from field.
   */
  public function getImage() {
    $image = [];
    $fid = $this->get('image')->target_id;
    if (isset($fid)) {
      $file = File::load($fid);
      if ($file instanceof FileInterface) {
        $image = [
          '#type' => 'image',
          '#theme' => 'image_style',
          '#style_name' => 'thumbnail',
          '#uri' => $file->getFileUri(),
          '#width' => 100,
        ];
        $renderer = \Drupal::service('renderer');
        $image = $renderer->render($image);
      }
    }
    else {
      $image = '{no image}';
    }
    return $image;
  }

  /**
   * Return Phone from field.
   */
  public function getPhone() {
    return $this->get('phone')->value;
  }

  /**
   * Return email from field.
   */
  public function getEmail() {
    return $this->get('email')->value;
  }

  /**
   * Return date of creation from field.
   */
  public function getDate() {
    $time = time();
    return $time;
  }

}
