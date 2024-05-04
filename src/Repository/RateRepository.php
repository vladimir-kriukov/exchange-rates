<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }

    public function isFieldValueExist(string $field, string $value): bool
    {
        return $this->createQueryBuilder('p')
                ->select('COUNT(1)')
                ->andWhere("p.$field = :value")
                ->setParameter('value', $value)
                ->getQuery()
                ->getSingleScalarResult() > 0;
    }
}
