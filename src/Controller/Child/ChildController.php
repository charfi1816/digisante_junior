<?php

namespace App\Controller\Child;

use App\Entity\JournalEntry;
use App\Entity\PainZone;
use App\Entity\User;
use App\Form\JournalEntryType;
use App\Repository\JournalEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChildController extends AbstractController
{
    #[Route('/child', name: 'app_child')]
    public function index(JournalEntryRepository $repository): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getChild() === null) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();
        $journal = $repository->findTodayByChild($child);

        // Calculate daily screen time.
        $percentage = 0;

        if ($journal !== null && $child->getDailyScreenLimit() > 0) {
            $percentage = min(100, $journal->getTotalMinutes() / $child->getDailyScreenLimit() * 100);
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
        EntityManagerInterface $em,
        Request                $request
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User || $user->getChild() === null) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();

        // Find or create today's journal.
        $journal = $repository->findTodayByChild($child);

        if ($journal === null) {
            $journal = new JournalEntry();
            $journal->setChild($child);
        }

        // Retrieve existing pain zones.
        $painZones = [];

        foreach ($journal->getPainZones() as $painZone) {
            $painZones[$painZone->getZone()] = $painZone;
        }

        $form = $this->createForm(JournalEntryType::class, $journal);

        // Pre-fill pain intensities.
        foreach (PainZone::ZONES as $zone => $details) {
            $form->get('painZones')->get($zone)->setData(
                isset($painZones[$zone]) ? $painZones[$zone]->getIntensity() : 0
            );
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($journal);

                // Save submitted pain intensities.
                foreach (PainZone::ZONES as $zone => $details) {
                    $intensity = (int)$form->get('painZones')->get($zone)->getData();
                    $painZone = $painZones[$zone] ?? null;

                    if ($intensity === 0 && $painZone !== null) {
                        $journal->removePainZone($painZone);
                    } elseif ($intensity > 0) {
                        if ($painZone === null) {
                            $painZone = new PainZone();
                            $painZone->setZone($zone);
                            $journal->addPainZone($painZone);
                            $em->persist($painZone);
                        }

                        $painZone->setIntensity($intensity);
                    }
                }

                $em->flush();

                $this->addFlash('success', 'Journal enregistré avec succès.');
                return $this->redirectToRoute('app_child');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l’enregistrement du journal.');
            }
        }

        return $this->render('child/journal/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
