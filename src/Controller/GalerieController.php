<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JeuxVideosRepository;

class GalerieController extends AbstractController
{
    #[Route('/galerie', name: 'galerie')]
    public function index(Request $request, JeuxVideosRepository $jeuxRepository): Response
    {
        // Récupérer les genres distincts à partir des jeux vidéos
        $genres = $jeuxRepository->findDistinctGenres();

        // Récupérer les paramètres de filtrage depuis la requête
        $genre = $request->query->get('genre');
        $prix = $request->query->get('prix'); // Utilisation d'un seul paramètre pour le prix

        // Appeler la méthode de repository avec filtrage si nécessaire
        if ($genre || $prix) {
            $jeux = $jeuxRepository->findByFilters($genre, $prix);
        } else {
            // Si pas de filtre, récupérer tous les jeux
            $jeux = $jeuxRepository->findAll();
        }

        // Renvoyer la vue avec les jeux récupérés (filtrés ou non)
        return $this->render('galerie/galerie.html.twig', [
            'jeux' => $jeux,
            'genres' => $genres, // Passer les genres à la vue
        ]);
    }
}
