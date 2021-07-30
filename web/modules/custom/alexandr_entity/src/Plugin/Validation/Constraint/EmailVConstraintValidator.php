<?php

namespace Drupal\alexandr_entity\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class provides validation for Email field.
 *
 * @package Drupal\alexandr_entity\Plugin\Validation\Constraint
 */
class EmailVConstraintValidator extends ConstraintValidator {

  /**
   * @param mixed $value
   * @param \Symfony\Component\Validator\Constraint $constraint
   */
  public function validate($value, Constraint $constraint) {
    if (!filter_var($value->value, FILTER_VALIDATE_EMAIL) || !preg_match('/^[A-Za-z-_]+[@]+[a-z]{2,12}+[.]+[a-z]{2,7}+$/', $value->value)) {
      $this->context->addViolation($constraint->message);
    }

  }

}
