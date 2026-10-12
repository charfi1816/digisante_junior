<?php

namespace App\Controller;

use App\Form\PainZoneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PainController extends AbstractController
{
    #[Route('/enfant/douleurs', name: 'app_child_pain')]
    public function index(): Response
    {
        // Create the pain form
        $form = $this->createForm(PainZoneType::class);

        return $this->render('pain/index.html.twig', [
            'form' => $form,
        ]);
    }
}
