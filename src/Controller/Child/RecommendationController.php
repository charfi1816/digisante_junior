<?php

namespace App\Controller\Child;

use App\Entity\User;
use App\Repository\JournalEntryRepository;
use App\Service\RecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecommendationController extends AbstractController
{
    #[Route('/child/recommendations', name: 'app_child_recommendations')]
    public function index(
        JournalEntryRepository $journalEntryRepository,
        RecommendationService  $recommendationService
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getChild() === null) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();

        // Find today's journal.
        $todayJournal = $journalEntryRepository->findTodayByChild($child);

        if ($todayJournal === null) {
            return $this->redirectToRoute('app_child_journal');
        }

        // Get personalized recommendations.
        $recommendations = $recommendationService->getRecommendations($todayJournal);

        return $this->render('recommendation/index.html.twig', [
            'child' => $child,
            'todayJournal' => $todayJournal,
            'recommendations' => $recommendations,
        ]);
    }
}
