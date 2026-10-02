<?php

namespace App\Entity;

use App\Repository\PainZoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PainZoneRepository::class)]
class PainZone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $zone = null;

    #[ORM\Column]
    private ?int $intensity = null;

    #[ORM\ManyToOne(inversedBy: 'painZones')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JournalEntry $journalEntry = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZone(): ?string
    {
        return $this->zone;
    }

    public function setZone(string $zone): static
    {
        $this->zone = $zone;

        return $this;
    }

    public function getIntensity(): ?int
    {
        return $this->intensity;
    }

    public function setIntensity(int $intensity): static
    {
        $this->intensity = $intensity;

        return $this;
    }

    public function getJournalEntry(): ?JournalEntry
    {
        return $this->journalEntry;
    }

    public function setJournalEntry(?JournalEntry $journalEntry): static
    {
        $this->journalEntry = $journalEntry;

        return $this;
    }
}
