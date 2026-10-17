<?php

namespace App\Controller\Admin;

use App\Entity\WellnessContent;
use App\Form\WellnessContentType;
use App\Repository\WellnessContentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/wellness-content', name: 'admin_wellness_content_')]
#[IsGranted('ROLE_ADMIN')]
final class WellnessContentController extends AbstractController
{

    #[Route(name: 'index', methods: ['GET'])]
    public function index(WellnessContentRepository $wellnessContentRepository): Response
    {
        return $this->render('admin/wellness_content/index.html.twig', [
            'wellness_contents' => $wellnessContentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wellnessContent = new WellnessContent();
        $form = $this->createForm(WellnessContentType::class, $wellnessContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wellnessContent);
            $entityManager->flush();

            return $this->redirectToRoute('admin_wellness_content_index');
        }

        return $this->render('admin/wellness_content/new.html.twig', [
            'wellness_content' => $wellnessContent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(WellnessContent $wellnessContent): Response
    {
        return $this->render('admin/wellness_content/show.html.twig', [
            'wellness_content' => $wellnessContent,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        WellnessContent        $wellnessContent,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(WellnessContentType::class, $wellnessContent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_wellness_content_index');
        }

        return $this->render('admin/wellness_content/edit.html.twig', [
            'wellness_content' => $wellnessContent,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request                $request,
        WellnessContent        $wellnessContent,
        EntityManagerInterface $entityManager
    ): Response
    {
        if ($this->isCsrfTokenValid(
            'delete' . $wellnessContent->getId(),
            $request->getPayload()->getString('_token')
        )) {
            $entityManager->remove($wellnessContent);
            $entityManager->flush();
            $this->addFlash('success', 'Le contenu bien-être a été supprimé avec succès.');
        }

        return $this->redirectToRoute('admin_wellness_content_index');
    }
}
