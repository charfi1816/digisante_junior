<?php

namespace App\Entity;

use App\Repository\ChildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DivisibleBy;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

#[ORM\Entity(repositoryClass: ChildRepository::class)]
class Child
{
    /**
     * Available avatars for child profiles.
     */

    public const AVATARS = [
        'fox' => ['name' => 'Renard malin'],
        'panda' => ['name' => 'Panda calme'],
        'cat' => ['name' => 'Chat curieux'],
        'lion' => ['name' => 'Lion courageux'],
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'First name is required.')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Last name is required.')]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[NotNull(message: 'Birth date is required.')]
    #[Range(
        notInRangeMessage: 'The child must be between 8 and 14 years old.',
        min: 'today -15 years +1 day',
        max: 'today -8 years'
    )]
    private ?\DateTimeImmutable $birthDate = null;

    #[ORM\Column(length: 255)]
    #[NotBlank(message: 'Avatar is required.')]
    #[Choice(
        choices: ['fox', 'panda', 'cat', 'lion'],
        message: 'Please select a valid avatar.'
    )]
    private ?string $avatar = null;

    #[ORM\Column]
    #[NotNull(message: 'Daily screen limit is required.')]
    #[Range(
        notInRangeMessage: 'Daily screen limit must be between 15 and 480 minutes.',
        min: 15,
        max: 480
    )]
    #[DivisibleBy(
        value: 15,
        message: 'Daily screen limit must be in 15-minute increments.'
    )]
    private ?int $dailyScreenLimit = 120;

    #[ORM\ManyToOne(inversedBy: 'children')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $parent = null;

    #[ORM\OneToOne(inversedBy: 'child', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $account = null;

    /**
     * @var Collection<int, JournalEntry>
     */
    #[ORM\OneToMany(targetEntity: JournalEntry::class, mappedBy: 'child', orphanRemoval: true)]
    private Collection $journalEntries;

    public function __construct()
    {
        $this->journalEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeImmutable $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getDailyScreenLimit(): ?int
    {
        return $this->dailyScreenLimit;
    }

    public function setDailyScreenLimit(int $dailyScreenLimit): static
    {
        $this->dailyScreenLimit = $dailyScreenLimit;

        return $this;
    }

    public function getParent(): ?User
    {
        return $this->parent;
    }

    public function setParent(?User $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Returns the child's full name.
     */

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * Calculates the child's current age from their birth date.
     */

    public function getAge(): ?int
    {
        return $this->birthDate?->diff(new \DateTimeImmutable())->y;
    }

    public function getAccount(): ?User
    {
        return $this->account;
    }

    public function setAccount(User $account): static
    {
        $this->account = $account;

        return $this;
    }

    /**
     * @return Collection<int, JournalEntry>
     */
    public function getJournalEntries(): Collection
    {
        return $this->journalEntries;
    }

    public function addJournalEntry(JournalEntry $journalEntry): static
    {
        if (!$this->journalEntries->contains($journalEntry)) {
            $this->journalEntries->add($journalEntry);
            $journalEntry->setChild($this);
        }

        return $this;
    }


}
