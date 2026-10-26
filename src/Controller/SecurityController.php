<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(): Response
    {
        return $this->redirectToRoute('app_login_parent');
    }

    #[Route('/login/parent', name: 'app_login_parent')]
    public function loginParent(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login.html.twig', [
            'last_username' => '',
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/login/child', name: 'app_login_child')]
    public function loginChild(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login_child.html.twig', [
            'last_username' => '',
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }


    #[Route('/login/admin', name: 'app_login_admin')]
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('security/login_admin.html.twig', [
            'last_username' => '',
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException(
            'This method is intercepted by the Symfony firewall.'
        );
    }
}
