<?php

namespace App\Entity;

use App\Repository\JournalEntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DivisibleBy;
use Symfony\Component\Validator\Constraints\Range;


/**
 * Represents a child's daily screen time journal entry.
 *
 * Each child can have only one journal entry per day.
 */
#[ORM\Entity(repositoryClass: JournalEntryRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_JOURNAL_ENTRY_CHILD_DATE', columns: ['child_id', 'date'])]
class JournalEntry
{

    /**
     * Screen time limits in minutes.
     */
    public const SCREEN_TIME_MIN = 0;
    public const SCREEN_TIME_MAX = 360;
    public const SCREEN_TIME_STEP = 15;
    public const DAILY_SCREEN_TIME_MAX = 960;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'TV screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'TV screen time must be in 15-minute increments.'
    )]
    private ?int $tvScreen = 0;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'PC screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'PC screen time must be in 15-minute increments.'
    )]
    private ?int $pcScreen = 0;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Phone screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'Phone screen time must be in 15-minute increments.'
    )]
    private ?int $phoneScreen = 0;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Tablet screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'Tablet screen time must be in 15-minute increments.'
    )]
    private ?int $tabletScreen = 0;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Console screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'Console screen time must be in 15-minute increments.'
    )]
    private ?int $consoleScreen = 0;

    #[ORM\Column]
    #[Range(
        notInRangeMessage: 'Other screen time must be between 0 and 360 minutes.',
        min: self::SCREEN_TIME_MIN,
        max: self::SCREEN_TIME_MAX
    )]
    #[DivisibleBy(
        value: self::SCREEN_TIME_STEP,
        message: 'Other screen time must be in 15-minute increments.'
    )]
    private ?int $otherScreen = 0;

    #[ORM\ManyToOne(inversedBy: 'journalEntries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Child $child = null;

    /**
     * @var Collection<int, PainZone>
     */
    #[ORM\OneToMany(targetEntity: PainZone::class, mappedBy: 'journalEntry', orphanRemoval: true)]
    private Collection $painZones;


    /**
     * @var Collection<int, ContentRecommendation>
     */
    #[ORM\OneToMany(
        targetEntity: ContentRecommendation::class,
        mappedBy: 'journalEntry',
        orphanRemoval: true
    )]
    private Collection $contentRecommendations;

    /**
     * Initializes the journal date and related collections.
     */
    public function __construct()
    {
        $this->date = new \DateTimeImmutable('today');
        $this->painZones = new ArrayCollection();
        $this->contentRecommendations = new ArrayCollection();
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
    #[Range(
        notInRangeMessage: 'Total daily screen time cannot exceed 960 minutes.',
        max: self::DAILY_SCREEN_TIME_MAX
    )]
    public function getTotalMinutes(): int
    {
        return $this->tvScreen
            + $this->pcScreen
            + $this->phoneScreen
            + $this->tabletScreen
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

    /**
     * @return Collection<int, ContentRecommendation>
     */
    public function getContentRecommendations(): Collection
    {
        return $this->contentRecommendations;
    }

    public function addContentRecommendation(ContentRecommendation $contentRecommendation): static
    {
        if (!$this->contentRecommendations->contains($contentRecommendation)) {
            $this->contentRecommendations->add($contentRecommendation);
            $contentRecommendation->setJournalEntry($this);
        }

        return $this;
    }

}
