<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class provides validation filters for Phone field.
 *
 * @package Drupal\alexandr_entity\Plugin\Validation\Constraint
 */
class PhoneVConstraintValidator extends ConstraintValidator {

  /**
   * @param mixed $value
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  public function validate($value, Constraint $constraint) {
    if (!preg_match('/^[0-9]{10}/', $value->value)) {
      $this->context->addViolation($constraint->message);
    }

  }

}
