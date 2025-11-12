<?php

namespace App\Validator;

use App\Repository\CategoryRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class UniqueCategoryNameValidator extends ConstraintValidator
{

    public function __construct(
        private readonly CategoryRepository $categoryRepository
    )
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        /* @var UniqueCategoryName $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $category = $this->categoryRepository->findBy(['name' => $value]);

        if (!$category) {
            return;
        }

        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation()
        ;
    }
}
