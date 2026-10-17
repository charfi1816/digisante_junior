<?php

namespace App\Controller\Child;

use App\Entity\JournalEntry;
use App\Entity\PainZone;
use App\Entity\User;
use App\Form\JournalEntryType;
use App\Repository\JournalEntryRepository;
use App\Service\JournalService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChildController extends AbstractController
{
    #[Route('/child', name: 'app_child')]
    public function index(
        JournalEntryRepository $repository,
        JournalService         $journalService
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getChild() === null) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();
        $journal = $repository->findTodayByChild($child);

        $percentage = 0;

        if ($journal !== null) {
            $percentage = $journalService->calculateScreenPercentage($journal);
        }

        return $this->render('child/dashboard/index.html.twig', [
            'child' => $child,
            'todayJournal' => $journal,
            'percentage' => $percentage,
        ]);
    }

    #[Route('/child/journal', name: 'app_child_journal')]
    public function journal(
        JournalEntryRepository $repository,
        JournalService         $journalService,
        Request                $request
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getChild() === null) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();

        $journal = $repository->findTodayByChild($child);

        if ($journal === null) {
            $journal = new JournalEntry();
            $journal->setChild($child);
        }

        $painZones = [];

        foreach ($journal->getPainZones() as $painZone) {
            $painZones[$painZone->getZone()] = $painZone;
        }

        $form = $this->createForm(JournalEntryType::class, $journal);

        foreach (PainZone::ZONES as $zone => $details) {
            $intensity = isset($painZones[$zone])
                ? $painZones[$zone]->getIntensity()
                : 0;

            $form->get('painZones')->get($zone)->setData($intensity);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $painIntensities = [];

            foreach (PainZone::ZONES as $zone => $details) {
                $painIntensities[$zone] = $form
                    ->get('painZones')
                    ->get($zone)
                    ->getData();
            }

            $success = $journalService->persistJournal(
                $journal,
                $painIntensities
            );

            if ($success) {
                $this->addFlash(
                    'success',
                    'Journal enregistré avec succès.'
                );

                return $this->redirectToRoute('app_child');
            }

            $this->addFlash(
                'danger',
                'Une erreur est survenue lors de l’enregistrement du journal.'
            );
        }

        return $this->render('child/journal/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
