<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Rate;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Validator that provided rate exist in rates table in the provided field
 */
class RateExistsValidator extends ConstraintValidator
{
    private readonly RateRepository $repository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Rate::class);
    }

    /**
     * @throws UnexpectedValueException
     * @throws UnexpectedTypeException
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof RateExists) {
            throw new UnexpectedTypeException($constraint, RateExists::class);
        }

        /* @var RateExists $constraint */

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $field = $constraint->field;

        if ($field === '') {
            $field = $this->context->getPropertyPath();
        }

        if (!$this->repository->isFieldValueExist($field, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
