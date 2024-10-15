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
        
        // Vérifie si des jeux existent dans MySQL, sinon retourne un message d'erreur
        if (!$games) {
            $this->addFlash('warning', 'Aucun jeu trouvé dans la base de données MySQL.');
            return $this->redirectToRoute('admin_dashboard'); // Remplace par la route adéquate
        }

        // Récupérer les agences depuis MySQL
        $agencies = $entityManager->getRepository(Agence::class)->findAll();

        // Récupérer les statistiques de ventes par jeu (MongoDB)
        $gameSales = $documentManager->getRepository(GameSales::class)->findAll();

        // Insérer des statistiques de ventes par jeu dans MongoDB (si nécessaire)
        foreach ($games as $game) {
            $existingGameSale = $documentManager->getRepository(GameSales::class)->findOneBy(['gameId' => $game->getId()]);
            
            // Si les statistiques n'existent pas encore pour ce jeu, on les crée
            if (!$existingGameSale) {
                $newGameSale = new GameSales();
                $newGameSale->setGameId($game->getId()); // Utiliser gameId pour référencer le jeu MySQL
                $newGameSale->setGenre($game->getGenre()); // Suppose que le jeu a un genre défini
                $newGameSale->setCopiesSold(mt_rand(100, 1000)); // Exemple, tu peux définir une vraie valeur
                $newGameSale->setTotalRevenue(mt_rand(10000, 100000)); // Exemple, valeur fictive
                $newGameSale->setSaleDate(new \DateTime()); // Ajoute la date d'achat

                // Persiste et flush les nouvelles données dans MongoDB
                $documentManager->persist($newGameSale);
            }
        }

        // Enregistrer les changements dans MongoDB
        $documentManager->flush();

        // Récupérer les statistiques de ventes par genre (MongoDB)
        $genreSales = $documentManager->getRepository(GenreSales::class)->findAll();

        // Récupérer les statistiques de ventes par agence (MongoDB)
        $agencySales = $documentManager->getRepository(AgencySales::class)->findAll();

        // Récupérer les genres depuis les jeux (MySQL)
        $genres = array_unique(array_map(function($game) {
            return $game->getGenre();
        }, $games));

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

                // Ajouter les dates et données de ventes
                if ($sale->getSaleDate()) {
                    $salesDates[] = $sale->getSaleDate()->format('Y-m-d'); // Date d'achat
                    $salesData[] = $sale->getCopiesSold(); // Quantité vendue
                }
            }
        }

        // Vérifie également si les ventes par genre ou par agence existent
        if (!$genreSales || !$agencySales) {
            $this->addFlash('warning', 'Pas de statistiques de ventes disponibles.');
        }

        // Récupérer le filtre actuel de la requête ou définir une valeur par défaut
        $filterType = $request->query->get('filter_type', 'game'); // Par défaut 'game'
        $filterValue = $request->query->get('filter_value', ''); // Par défaut vide

        return $this->render('admin/sales_dashboard.html.twig', [
            'games' => $games,
            'agencies' => $agencies,
            'gameSales' => $gameSales,
            'genreSales' => $genreSales,
            'agencySales' => $agencySales,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'filter_type' => $filterType,  // Ajout du filtre type
            'filter_value' => $filterValue, // Ajout du filtre valeur
            'sales_dates' => $salesDates,   // Ajout des dates de vente
            'sales_data' => $salesData,     // Ajout des données de vente
            'genres' => $genres,            // Ajout des genres ici
        ]);
    }
}