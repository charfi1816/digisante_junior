<?php

namespace App\Service;

use App\Entity\JournalEntry;
use App\Repository\WellnessContentRepository;

class RecommendationService
{
    private const SCREEN_LIMIT = 120;
    private const PAIN_LIMIT = 3;

    public function __construct(
        private readonly WellnessContentRepository $wellnessContentRepository,
    )
    {
    }

    /**
     * Generates personalized recommendations based on the child's daily journal.
     */
    public function getRecommendations(JournalEntry $journal): array
    {
        $recommendations = [];
        $total = $journal->getTotalMinutes();
        $limit = $journal->getChild()->getDailyScreenLimit();

        // Recommend a screen break based on the child's daily screen time.
        if ($total > $limit) {
            $recommendations[] = [
                'title' => 'Trouve ton équilibre',
                'message' => 'Tu as dépassé ta limite quotidienne. '
                    . 'Demain, essaie de prévoir une pause plus tôt !',
                'resource' => $this->wellnessContentRepository->findByTitle(
                    'Faire une pause écran'
                ),
            ];
        } elseif ($total >= self::SCREEN_LIMIT) {
            $recommendations[] = [
                'title' => 'Pense à faire une pause',
                'message' => 'Tu as passé au moins deux heures devant les écrans. '
                    . 'Profites-en pour jouer, dessiner ou bouger un peu !',
                'resource' => $this->wellnessContentRepository->findByTitle(
                    'Faire une pause écran'
                ),
            ];
        }

        // Suggest exercises based on the reported pain zones.
        foreach ($journal->getPainZones() as $painZone) {
            if ($painZone->getZone() === 'neck'
                && $painZone->getIntensity() >= self::PAIN_LIMIT) {
                $recommendations[] = [
                    'title' => 'Détends doucement ta nuque',
                    'message' => 'Fais une pause et détends ta nuque sans forcer. '
                        . 'Si la douleur continue, parles-en à un adulte.',
                    'resource' => $this->wellnessContentRepository->findByTitle(
                        'Étirements du cou'
                    ),
                ];
            }

            if ($painZone->getZone() === 'eyes') {
                $recommendations[] = [
                    'title' => 'Repose tes yeux',
                    'message' => 'Essaie la règle 20-20-20 : toutes les 20 minutes, '
                        . 'regarde au loin pendant 20 secondes.',
                    'resource' => $this->wellnessContentRepository->findByTitle(
                        'La règle du 20-20-20'
                    ),
                ];
            }
        }

        // Display a positive message when no recommendation is needed.
        if ($recommendations === []) {
            $recommendations[] = [
                'title' => 'Bravo pour aujourd’hui !',
                'message' => 'Continue à prendre soin de toi et à varier tes activités.',
                'resource' => null,
            ];
        }

        // Limit the number of recommendations displayed to the child.
        return array_slice($recommendations, 0, 3);
    }
}
