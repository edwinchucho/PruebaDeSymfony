<?php

namespace App\Validator;

use App\Repository\ProductRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueCodeValidator extends ConstraintValidator
{

    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validate($value, Constraint $constraint)
    {
        $existingCode = $this->repository->findOneBy(['code' => $value]);

        if (!$existingCode) {
            return;
        }

        /* @var $constraint \App\Validator\UniqueCode */

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
