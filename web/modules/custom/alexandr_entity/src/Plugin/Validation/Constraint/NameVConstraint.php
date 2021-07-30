<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "NameV",
 *   label = @Translation("Name validation"),
 * )
 */
class NameVConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The Name is too short. Enter more than two symbols';

}
