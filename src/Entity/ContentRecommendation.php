<?php

namespace App\Entity;

use App\Repository\ContentRecommendationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentRecommendationRepository::class)]
class ContentRecommendation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'contentRecommendations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?JournalEntry $journalEntry = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?WellnessContent $wellnessContent = null;

    /**
     * Initializes the creation date when the recommendation is created.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getJournalEntry(): ?JournalEntry
    {
        return $this->journalEntry;
    }

    public function setJournalEntry(?JournalEntry $journalEntry): void
    {
        $this->journalEntry = $journalEntry;
    }

    public function getWellnessContent(): ?WellnessContent
    {
        return $this->wellnessContent;
    }

    public function setWellnessContent(?WellnessContent $wellnessContent): void
    {
        $this->wellnessContent = $wellnessContent;
    }


}
