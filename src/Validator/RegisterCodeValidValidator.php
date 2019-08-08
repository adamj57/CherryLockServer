<?php

namespace App\Validator;

use App\Entity\RegisterCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RegisterCodeValidValidator extends ConstraintValidator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\RegisterCodeValid */

        if (null === $value || '' === $value) {
            return;
        }

        $registerCode = $this->entityManager->getRepository(RegisterCode::class)
            ->findOneByCode($value);

        if ($registerCode != null && $registerCode->getValid()) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
