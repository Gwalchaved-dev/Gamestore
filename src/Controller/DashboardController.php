<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Repository\JeuxVideosRepository;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    #[Route('/sales_dashboard', name: 'sales_dashboard')]
    public function salesDashboard(DocumentManager $dm, JeuxVideosRepository $jeuxRepository, Request $request): Response
    {
        // Récupérer le type de filtre sélectionné
        $filterType = $request->get('filter_type', 'game');
        $filterValue = $request->get('filter_value', null);

        // Variables pour stocker les résultats
        $salesData = [];
        $salesDates = [];
        $games = [];
        $agencies = [];
        $genres = [];

        // Récupérer les jeux pour le filtrage
        if ($filterType === 'game') {
            $games = $jeuxRepository->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(GameSales::class)->findBy(['gameId' => $filterValue]);
            }
        }
        // Récupérer les agences pour le filtrage
        elseif ($filterType === 'agency') {
            $agencies = $dm->getRepository(AgencySales::class)->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(AgencySales::class)->findBy(['agencyId' => $filterValue]);
            }
        }
        // Récupérer les genres pour le filtrage
        elseif ($filterType === 'genre') {
            $genres = $dm->getRepository(GenreSales::class)->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(GenreSales::class)->findBy(['genre' => $filterValue]);
            }
        }

        // Extraire les dates des ventes
        foreach ($salesData as $sale) {
            $saleDate = $sale->getSaleDate();
            if ($saleDate !== null) {
                $salesDates[] = $saleDate->format('Y-m-d');
            }
        }

        return $this->render('admin/sales_dashboard.html.twig', [
            'sales' => $salesData,  // Données des ventes
            'sales_dates' => $salesDates,  // Dates des ventes pour le graphique
            'filter_type' => $filterType,  // Type de filtre sélectionné (jeu, agence, genre)
            'filter_value' => $filterValue,  // Valeur du filtre sélectionné
            'games' => $games,  // Liste des jeux
            'agencies' => $agencies,  // Liste des agences
            'genres' => $genres,  // Liste des genres
        ]);
    }
}