<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "EmailV",
 *   label = @Translation("Email validation"),
 * )
 */
class EmailVConstraint extends Constraint {

  /**
   * @var string
   */
  public $message = 'The email is incorrect.';

}
