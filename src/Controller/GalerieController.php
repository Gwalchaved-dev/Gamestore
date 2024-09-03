<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GalerieController extends AbstractController
{
    #[Route('/galerie', name: 'galerie')]
    public function index(): Response
    {
        return $this->render('galerie/galerie.html.twig', [
            'controller_name' => 'GalerieController',
        ]);
    }
}
