<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "PhoneV",
 *   label = @Translation("Phone validation"),
 * )
 */
class PhoneVConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The phone is incorrect.';

}
