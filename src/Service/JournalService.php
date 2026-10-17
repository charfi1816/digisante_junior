<?php

namespace App\Service;

use App\Entity\JournalEntry;
use App\Entity\PainZone;
use Doctrine\ORM\EntityManagerInterface;

final class JournalService
{
    public function __construct(
        private EntityManagerInterface $em
    )
    {
    }

    public function persistJournal(
        JournalEntry $journal,
        array        $painIntensities
    ): bool
    {
        try {
            $painZones = [];

            foreach ($journal->getPainZones() as $painZone) {
                $painZones[$painZone->getZone()] = $painZone;
            }

            foreach (PainZone::ZONES as $zone => $details) {
                $intensity = (int)($painIntensities[$zone] ?? 0);
                $painZone = $painZones[$zone] ?? null;

                if ($intensity === 0 && $painZone !== null) {
                    $journal->removePainZone($painZone);
                } elseif ($intensity > 0) {
                    if ($painZone === null) {
                        $painZone = new PainZone();
                        $painZone->setZone($zone);
                        $journal->addPainZone($painZone);
                    }

                    $painZone->setIntensity($intensity);
                }
            }

            if ($journal->getId() === null) {
                $this->em->persist($journal);
            }

            $this->em->flush();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function calculateScreenPercentage(
        JournalEntry $journal
    ): float
    {
        $screenLimit = $journal->getChild()->getDailyScreenLimit();

        if ($screenLimit <= 0) {
            return 0;
        }

        return min(
            100,
            $journal->getTotalMinutes() / $screenLimit * 100
        );
    }
}
