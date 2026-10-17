<?php

namespace App\Repository;

use App\Entity\WellnessContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WellnessContent>
 */
class WellnessContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WellnessContent::class);
    }

    public function findByTitle(string $title): ?WellnessContent
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.title = :title')
            ->setParameter('title', $title)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

