<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Rate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Rate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rate[]    findAll()
 * @method Rate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rate::class);
    }


    /**
     * Does field value exist.
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function isFieldValueExist(string $field, string $value): bool
    {
        return $this->createQueryBuilder('p')
                ->select('COUNT(1)')
                ->andWhere("p.$field = :value")
                ->setParameter('value', $value)
                ->getQuery()
                ->getSingleScalarResult()
            > 0;
    }
}
