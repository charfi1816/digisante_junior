<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChildType;
use App\Entity\Child;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

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
    public function new(Request                     $request,
                        EntityManagerInterface      $entityManager,
                        UserPasswordHasherInterface $passwordHasher): Response
    {

        $child = new Child();

        $form = $this->createForm(ChildType::class, $child);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $childAccount = new User();

            // Generate a unique child username.
            $slugger = new AsciiSlugger();
            $pseudo = strtolower($slugger->slug($child->getFirstName())->toString());

            $username = $pseudo;
            $number = 2;

            while ($entityManager->getRepository(User::class)->findOneBy(['username' => $username])) {
                $username = $pseudo . $number;
                $number++;
            }

            $childAccount->setUsername($username);

            $childAccount->setRoles([User::ROLE_CHILD]);
            $plainPassword = $form->get('plainPassword')->getData();

            $childAccount->setPassword(
                $passwordHasher->hashPassword($childAccount, $plainPassword)
            );

            // Link the child profile to the logged-in parent.
            $child->setParent($this->getUser());
            $child->setAccount($childAccount);
            $entityManager->persist($childAccount);
            $entityManager->persist($child);
            $entityManager->flush();
            // Show the generated username to the parent.
            $this->addFlash(
                'success',
                'Compte créé avec succès ! Identifiant de connexion : ' . $username
            );

            // Redirect to the parent's children list after saving.
            return $this->redirectToRoute('app_parent');

        }

        // Display the form.
        return $this->render('parent/new_child.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
