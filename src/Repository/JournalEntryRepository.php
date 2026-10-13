<?php

namespace App\Repository;

use App\Entity\JournalEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Child;

/**
 * @extends ServiceEntityRepository<JournalEntry>
 */
class JournalEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JournalEntry::class);
    }


    public function findTodayByChild(Child $child): ?JournalEntry
    {
        return $this->findOneBy([
            'child' => $child,
            'date' => new \DateTimeImmutable('today'),
        ]);
    }


}
