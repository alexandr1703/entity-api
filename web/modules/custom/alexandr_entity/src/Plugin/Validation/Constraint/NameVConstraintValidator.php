<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class provides validation for Name field.
 *
 * @package Drupal\alexandr_entity\Plugin\Validation\Constraint
 */
class NameVConstraintValidator extends ConstraintValidator {

  /**
   * @param mixed $value
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  public function validate($value, Constraint $constraint) {
    if (strlen($value->value) < 2) {
      $this->context->addViolation($constraint->message);
    }

  }

}
