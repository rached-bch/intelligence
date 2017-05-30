<?php
namespace IT\PlatformBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ArrayContainsAlphanumericValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(is_array($value)) {
            foreach($value as $one_value) {
                if (!preg_match('/^[a-zA-Z0-9]+$/', $one_value, $matches)) {
                    // If you're using the new 2.5 validation API (you probably are!)
                    $this->context->buildViolation($constraint->message)
                        ->setParameter('%string%', $one_value)
                        ->addViolation();

                    // If you're using the old 2.4 validation API
                    /*
                    $this->context->addViolation(
                        $constraint->message,
                        array('%string%' => $one_value)
                    );
                    */
                }   
            }                
        }
    }
}