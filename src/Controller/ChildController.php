<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\JournalEntry;
use App\Form\JournalEntryType;
use App\Repository\JournalEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ChildController extends AbstractController
{
    #[Route('/enfant', name: 'app_child')]
    public function index(
        JournalEntryRepository $journalEntryRepository
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();

        if ($child === null) {
            throw $this->createNotFoundException('Child profile not found.');
        }

        // Find today's journal entry for this child.
        $todayJournal = $journalEntryRepository->findOneBy([
            'child' => $child,
            'date' => new \DateTimeImmutable('today'),
        ]);

        return $this->render('child/index.html.twig', [
            'child' => $child,
            'todayJournal' => $todayJournal,
        ]);
    }

    #[Route('/enfant/journal', name: 'app_child_journal')]
    public function journal(
        Request                $request,
        JournalEntryRepository $journalEntryRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $child = $user->getChild();

        if ($child === null) {
            throw $this->createNotFoundException('Child profile not found.');
        }

        // Find today's journal entry.
        $journalEntry = $journalEntryRepository->findOneBy([
            'child' => $child,
            'date' => new \DateTimeImmutable('today'),
        ]);

        // Create a new journal only if none exists today.
        if ($journalEntry === null) {
            $journalEntry = new JournalEntry();
            $journalEntry->setChild($child);
        }

        // Create the form and read submitted data.
        $form = $this->createForm(JournalEntryType::class, $journalEntry);
        $form->handleRequest($request);

        // Save the journal if the form is valid.
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($journalEntry);
            $entityManager->flush();

            return $this->redirectToRoute('app_child');
        }

        return $this->render('child/journal.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
