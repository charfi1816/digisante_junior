<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChildType;
use App\Entity\Child;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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


    #[Route('/parent/enfant/ajouter', name: 'app_parent_child_new')]
    public function new(Request $request): Response
    {
        // Create an empty child profile.
        $child = new Child();

        // Create the form using the child profile.
        $form = $this->createForm(ChildType::class, $child);

        // Read the submitted form data.
        $form->handleRequest($request);

        // Display the form.
        return $this->render('parent/new_child.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
