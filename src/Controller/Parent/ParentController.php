<?php

namespace App\Controller\Parent;

use App\Entity\Child;
use App\Entity\User;
use App\Form\ChildType;
use App\Service\ChildAccountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ParentController extends AbstractController
{
    #[Route('/parent', name: 'app_parent')]
    public function index(): Response
    {
        $parent = $this->getUser();

        if (!$parent instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $children = $parent->getChildren();

        return $this->render('parent/index.html.twig', [
            'children' => $children,
        ]);
    }

    #[Route('/parent/child/new', name: 'app_parent_child_new')]
    public function new(
        Request             $request,
        ChildAccountService $childAccountService
    ): Response
    {
        $parent = $this->getUser();

        if (!$parent instanceof User) {
            throw $this->createAccessDeniedException();
        }

        $child = new Child();

        $form = $this->createForm(ChildType::class, $child);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            $username = $childAccountService->createAccount(
                $child,
                $parent,
                $password
            );

            $this->addFlash(
                'success',
                'Compte créé avec succès ! Identifiant de connexion : ' . $username
            );

            return $this->redirectToRoute('app_parent');
        }

        return $this->render('parent/new_child.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
