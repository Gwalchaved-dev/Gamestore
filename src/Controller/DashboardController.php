<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Repository\JeuxVideosRepository;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use Psr\Log\LoggerInterface;
use App\Service\CommandSyncService;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractController
{
    private LoggerInterface $logger;
    private CommandSyncService $commandSyncService;

    public function __construct(LoggerInterface $logger, CommandSyncService $commandSyncService)
    {
        $this->logger = $logger;
        $this->commandSyncService = $commandSyncService;
    }

    #[Route('/sales_dashboard', name: 'sales_dashboard')]
    public function salesDashboard(DocumentManager $dm, JeuxVideosRepository $jeuxRepository, Request $request): Response
    {
        $filterType = $request->get('filter_type', 'game');
        $filterValue = $request->get('filter_value', null);

        $this->logger->info("Filter type: $filterType, Filter value: $filterValue");

        // Appel correct de la méthode `syncValidatedCommands`
        $this->commandSyncService->syncValidatedCommands();

        $salesData = [];
        $salesDates = [];
        
        // Récupérer les options de filtre
        $games = $jeuxRepository->findAll();
        $agencies = $dm->getRepository(AgencySales::class)->findAll();
        $genres = $dm->getRepository(GenreSales::class)->findAll();

        $this->logger->info("Games count: " . count($games));
        $this->logger->info("Agencies count: " . count($agencies));
        $this->logger->info("Genres count: " . count($genres));

        // Filtrer les ventes selon le filtre sélectionné
        if ($filterType === 'game' && $filterValue) {
            $salesData = $dm->getRepository(GameSales::class)->findBy(['gameId' => $filterValue]);
        } elseif ($filterType === 'agency' && $filterValue) {
            $salesData = $dm->getRepository(AgencySales::class)->findBy(['agencyId' => $filterValue]);
        } elseif ($filterType === 'genre' && $filterValue) {
            $salesData = $dm->getRepository(GenreSales::class)->findBy(['genre' => $filterValue]);
        }

        $this->logger->info("Sales data count: " . count($salesData));

        // Extraire les dates des ventes
        foreach ($salesData as $sale) {
            $salesDates[] = $sale->getSaleDate()->format('Y-m-d');
        }

        $this->logger->info("Sales dates count: " . count($salesDates));

        return $this->render('admin/sales_dashboard.html.twig', [
            'sales' => $salesData,
            'sales_dates' => $salesDates,
            'filter_type' => $filterType,
            'filter_value' => $filterValue,
            'games' => $games,
            'agencies' => $agencies,
            'genres' => $genres,
        ]);
    }
}