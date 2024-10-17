<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PolitiqueConfidentialiteController extends AbstractController
{
    #[Route('/politique-de-confidentialite', name: 'politique_confidentialite')]
    public function index(): Response
    {
        return $this->render('legal/politique_confidentialite.html.twig', [
            'controller_name' => 'PolitiqueConfidentialiteController',
        ]);
    }
}