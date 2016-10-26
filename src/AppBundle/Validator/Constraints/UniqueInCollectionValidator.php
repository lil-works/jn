<?php
namespace AppBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManager;
/**
 * @Annotation
 */
class UniqueInCollectionValidator extends ConstraintValidator
{
    public $message = 'This fingering is already in database';
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if( $this->entityManager->getRepository('AppBundle:Fingering')->findOneByFingers($value) ){
            $this->context->buildViolation($constraint->message)
                ->setParameter('%string%', "==== FIND IN DB ======")
                ->addViolation();

        }



    }

}