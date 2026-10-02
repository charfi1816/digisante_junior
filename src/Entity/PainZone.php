<?php

namespace App\Entity;

use App\Repository\PainZoneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: PainZoneRepository::class)]
class PainZone
{
    /**
     * Available pain zones for journal entries.
     */
    public const ZONES = [
        'eyes' => ['name' => 'Yeux'],
        'neck' => ['name' => 'Cou / nuque'],
        'back' => ['name' => 'Dos'],
        'hands' => ['name' => 'Mains / poignets'],
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Choice(
        choices: ['eyes', 'neck', 'back', 'hands'],
        message: 'Please select a valid pain zone.'
    )]
    private ?string $zone = null;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Pain intensity must be between 1 and 5.',
        min: 1,
        max: 5
    )]
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
