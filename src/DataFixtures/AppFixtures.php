<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Child;
use App\Entity\JournalEntry;
use App\Entity\PainZone;
use App\Entity\WellnessContent;
use App\Entity\ContentRecommendation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create the demo parent account.
        $parent = new User();
        $parent->setEmail('parent@digisante.local');
        $parent->setRoles([User::ROLE_PARENT]);
        $parent->setPassword(
            $this->passwordHasher->hashPassword($parent, 'parent123')
        );

        $manager->persist($parent);

        // Create the child's login account.
        $childAccount = new User();
        $childAccount->setUsername('lea');
        $childAccount->setRoles([User::ROLE_CHILD]);
        $childAccount->setPassword(
            $this->passwordHasher->hashPassword($childAccount, 'enfant123')
        );

        // Create the child profile linked to the parent and login account.
        $child = new Child();
        $child->setFirstName('Léa');
        $child->setLastName('Martin');
        $child->setBirthDate(new \DateTimeImmutable('today -12 years'));
        $child->setAvatar('fox');
        $child->setDailyScreenLimit(120);
        $child->setParent($parent);
        $child->setAccount($childAccount);

        $manager->persist($child);

        // Create today's journal entry for the demo child.
        $journal = new JournalEntry();
        $journal->setChild($child);
        $journal->setTvScreen(30);
        $journal->setPcScreen(45);
        $journal->setPhoneScreen(60);
        $journal->setTabletScreen(15);
        $journal->setConsoleScreen(30);
        $journal->setOtherScreen(0);

        $manager->persist($journal);

        // Add a neck pain reported for today's journal entry.
        $painZone = new PainZone();
        $painZone->setZone('neck');
        $painZone->setIntensity(3);
        $painZone->setJournalEntry($journal);

        $manager->persist($painZone);

        // Create a wellness content for screen-related eye fatigue.
        $content = new WellnessContent();
        $content->setType('exercise');
        $content->setTitle('La règle du 20-20-20');
        $content->setContent(
            'Toutes les 20 minutes devant un écran, regarde un objet éloigné pendant 20 secondes.'
        );

        // Create a wellness content for neck tension.
        $neckContent = new WellnessContent();
        $neckContent->setType('exercise');
        $neckContent->setTitle('Étirements du cou');
        $neckContent->setContent(
            'Fais quelques mouvements doux du cou et des épaules après une longue période devant un écran.'
        );

        $manager->persist($neckContent);

// Create a wellness content for taking a screen break.
        $content = new WellnessContent();
        $content->setType('article');
        $content->setTitle('Faire une pause écran');
        $content->setContent(
            'Une pause régulière permet de bouger, de reposer les yeux et de changer de position.'
        );

        $manager->persist($content);

// Create a wellness content about screen posture.
        $content = new WellnessContent();
        $content->setType('article');
        $content->setTitle('Bien s’installer devant un écran');
        $content->setContent(
            'Garde le dos droit, les pieds posés au sol et place l’écran à une distance confortable.'
        );

        $manager->persist($content);

        // Create a demo recommendation for the journal entry.
        $recommendation = new ContentRecommendation();
        $recommendation->setJournalEntry($journal);
        $recommendation->setWellnessContent($neckContent);

        $manager->persist($recommendation);

        $manager->flush();
    }
}
