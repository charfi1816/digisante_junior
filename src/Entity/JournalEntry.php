<?php

namespace App\Entity;

use App\Repository\JournalEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * Represents a child's daily screen time journal entry.
 *
 * Each child can have only one journal entry per day.
 */
#[ORM\Entity(repositoryClass: JournalEntryRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_JOURNAL_ENTRY_CHILD_DATE', columns: ['child_id', 'date'])]
class JournalEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?int $tvScreen = 0;

    #[ORM\Column]
    private ?int $pcScreen = 0;

    #[ORM\Column]
    private ?int $phoneScreen = 0;

    #[ORM\Column]
    private ?int $tabletScreen = 0;

    #[ORM\Column]
    private ?int $consoleScreen = 0;

    #[ORM\Column]
    private ?int $otherScreen = 0;

    #[ORM\ManyToOne(inversedBy: 'journalEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Child $child = null;

    /**
     * @var Collection<int, PainZone>
     */
    #[ORM\OneToMany(targetEntity: PainZone::class, mappedBy: 'journalEntry', orphanRemoval: true)]
    private Collection $painZones;

    public function __construct()
    {
        $this->painZones = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTvScreen(): ?int
    {
        return $this->tvScreen;
    }

    public function setTvScreen(int $tvScreen): static
    {
        $this->tvScreen = $tvScreen;

        return $this;
    }

    public function getPcScreen(): ?int
    {
        return $this->pcScreen;
    }

    public function setPcScreen(int $pcScreen): static
    {
        $this->pcScreen = $pcScreen;

        return $this;
    }

    public function getPhoneScreen(): ?int
    {
        return $this->phoneScreen;
    }

    public function setPhoneScreen(int $phoneScreen): static
    {
        $this->phoneScreen = $phoneScreen;

        return $this;
    }

    public function getTabletScreen(): ?int
    {
        return $this->tabletScreen;
    }

    public function setTabletScreen(int $tabletScreen): static
    {
        $this->tabletScreen = $tabletScreen;

        return $this;
    }

    public function getConsoleScreen(): ?int
    {
        return $this->consoleScreen;
    }

    public function setConsoleScreen(int $consoleScreen): static
    {
        $this->consoleScreen = $consoleScreen;

        return $this;
    }

    public function getOtherScreen(): ?int
    {
        return $this->otherScreen;
    }

    public function setOtherScreen(int $otherScreen): static
    {
        $this->otherScreen = $otherScreen;

        return $this;
    }

    public function getChild(): ?Child
    {
        return $this->child;
    }

    public function setChild(?Child $child): static
    {
        $this->child = $child;

        return $this;
    }


    /**
     * Calculates the total screen time in minutes.
     */
    public function getTotalMinutes(): int
    {
        return $this->tvScreen + $this->pcScreen + $this->phoneScreen + $this->tabletScreen
            + $this->consoleScreen
            + $this->otherScreen;
    }

    /**
     * @return Collection<int, PainZone>
     */
    public function getPainZones(): Collection
    {
        return $this->painZones;
    }

    public function addPainZone(PainZone $painZone): static
    {
        if (!$this->painZones->contains($painZone)) {
            $this->painZones->add($painZone);
            $painZone->setJournalEntry($this);
        }

        return $this;
    }

    public function removePainZone(PainZone $painZone): static
    {
        if ($this->painZones->removeElement($painZone)) {
            // set the owning side to null (unless already changed)
            if ($painZone->getJournalEntry() === $this) {
                $painZone->setJournalEntry(null);
            }
        }

        return $this;
    }


}
