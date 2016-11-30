<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueInCollection extends Constraint
{
    public $message = 'The error message (with %parameters%)';
    // The property path used to check wether objects are equal
    // If none is specified, it will check that objects are equal
    public $propertyPath = null;
}