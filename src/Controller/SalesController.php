<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Agence;
use App\Document\GameSales;
use App\Document\GenreSales;
use App\Document\AgencySales;
use App\Entity\JeuxVideos;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    #[Route('/admin/sales_dashboard', name: 'sales_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, DocumentManager $documentManager, Request $request): Response
    {
        // Récupérer les jeux depuis MySQL
        $games = $entityManager->getRepository(JeuxVideos::class)->findAll();
        
        if (!$games) {
            $this->addFlash('warning', 'Aucun jeu trouvé dans la base de données MySQL.');
            return $this->redirectToRoute('admin_dashboard'); // Assurez-vous que cette route existe
        }

        // Récupérer les agences depuis MySQL
        $agencies = $entityManager->getRepository(Agence::class)->findAll();

        // Récupérer les statistiques de ventes par jeu (MongoDB)
        $gameSales = $documentManager->getRepository(GameSales::class)->findAll();

        // Insérer des statistiques de ventes par jeu dans MongoDB si elles n'existent pas
        foreach ($games as $game) {
            $existingGameSale = $documentManager->getRepository(GameSales::class)->findOneBy(['gameId' => $game->getId()]);
            
            if (!$existingGameSale) {
                $newGameSale = new GameSales();
                $newGameSale->setGameId($game->getId());
                $newGameSale->setGenre($game->getGenre());
                $newGameSale->setCopiesSold(mt_rand(100, 1000));
                $newGameSale->setTotalRevenue(mt_rand(10000, 100000));
                $newGameSale->setSaleDate(new \DateTime());

                $documentManager->persist($newGameSale);
            }
        }

        // Enregistrer les changements dans MongoDB
        $documentManager->flush();

        // Récupérer les statistiques de ventes par genre et par agence depuis MongoDB
        $genreSales = $documentManager->getRepository(GenreSales::class)->findAll();
        $agencySales = $documentManager->getRepository(AgencySales::class)->findAll();

        // Récupérer les genres depuis les jeux (MySQL)
        $genres = array_unique(array_map(fn($game) => $game->getGenre(), $games));

        // Calcul des statistiques globales
        $totalSales = 0;
        $totalRevenue = 0;

        // Préparer les données de ventes (dates et copies vendues)
        $salesDates = [];
        $salesData = [];

        if ($gameSales) {
            foreach ($gameSales as $sale) {
                $totalSales += $sale->getCopiesSold();
                $totalRevenue += $sale->getTotalRevenue();

                if ($sale->getSaleDate()) {
                    $salesDates[] = $sale->getSaleDate()->format('Y-m-d');
                    $salesData[] = $sale->getCopiesSold();
                }
            }
        }

        if (!$genreSales || !$agencySales) {
            $this->addFlash('warning', 'Pas de statistiques de ventes disponibles.');
        }

        // Récupérer le filtre actuel de la requête ou définir une valeur par défaut
        $filterType = $request->query->get('filter_type', 'game');
        $filterValue = $request->query->get('filter_value', '');

        return $this->render('admin/sales_dashboard.html.twig', [
            'games' => $games,
            'agencies' => $agencies,
            'gameSales' => $gameSales,
            'genreSales' => $genreSales,
            'agencySales' => $agencySales,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'filter_type' => $filterType,
            'filter_value' => $filterValue,
            'sales_dates' => $salesDates,
            'sales_data' => $salesData,
            'genres' => $genres,
        ]);
    }
}
