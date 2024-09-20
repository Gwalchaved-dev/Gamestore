<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Remplace `Attribute\Route` par `Annotation\Route`
use App\Repository\JeuxVideosRepository;

class GalerieController extends AbstractController
{
    #[Route('/galerie', name: 'galerie')]
    public function index(Request $request, JeuxVideosRepository $jeuxRepository): Response
    {
        // Récupérer les paramètres de filtrage depuis la requête
        $genre = $request->query->get('genre');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');

        // Construire la requête de filtrage
        $jeux = $jeuxRepository->findByFilters($genre, $minPrice, $maxPrice);

        // Renvoyer la vue avec les jeux filtrés
        return $this->render('galerie/galerie.html.twig', [
            'jeux' => $jeux,
        ]);
    }
}
