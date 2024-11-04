<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Command;
use App\Repository\JeuxVideosRepository;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use App\Service\CommandSyncService;
use Psr\Log\LoggerInterface;

#[Route('/employee')]
#[IsGranted('ROLE_EMPLOYEE')]
class EmployeeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommandSyncService $commandSyncService;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, CommandSyncService $commandSyncService, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->commandSyncService = $commandSyncService;
        $this->logger = $logger;
    }

    #[Route('/space', name: 'app_employee')]
    public function employeeSpace(): Response
    {
        return $this->render('Employee/employee_space.html.twig');
    }

    #[Route('/dashboard', name: 'employee_dashboard')]
    public function dashboard(DocumentManager $dm, JeuxVideosRepository $jeuxRepository, Request $request): Response
    {
        $filterType = $request->get('filter_type', 'game');
        $filterValue = $request->get('filter_value', null);

        // Synchroniser les commandes validées de MySQL vers MongoDB
        $this->commandSyncService->syncValidatedCommands(); // Correction ici

        $salesData = [];
        $salesDates = [];
        $games = [];
        $agencies = [];
        $genres = [];

        // Récupérer les options de filtre
        $games = $jeuxRepository->findAll();
        $agencies = $dm->getRepository(AgencySales::class)->findAll();
        $genres = $dm->getRepository(GenreSales::class)->findAll();

        $this->logger->info("Filter type: $filterType, Filter value: $filterValue");

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

        return $this->render('Employee/employee_dashboard.html.twig', [
            'sales' => $salesData,
            'sales_dates' => $salesDates,
            'filter_type' => $filterType,
            'filter_value' => $filterValue,
            'games' => $games,
            'agencies' => $agencies,
            'genres' => $genres,
        ]);
    }

    #[Route('/stock', name: 'employee_stock')]
    public function stock(JeuxVideosRepository $jeuxRepository): Response
    {
        $jeux = $jeuxRepository->findAll();
        return $this->render('Employee/employee_stock.html.twig', [
            'jeux' => $jeux,
        ]);
    }

    #[Route('/orders', name: 'employee_orders')]
    public function orders(Request $request): Response
    {
        $status = $request->query->get('status', null);

        // Récupérer toutes les commandes ou filtrer par statut si sélectionné
        if ($status) {
            $orders = $this->entityManager->getRepository(Command::class)->findBy(['status' => $status]);
        } else {
            $orders = $this->entityManager->getRepository(Command::class)->findAll();
        }

        return $this->render('Employee/employee_orders.html.twig', [
            'orders' => $orders,
            'current_status' => $status
        ]);
    }

    #[Route('/orders/update/{id}', name: 'update_order_status')]
    public function updateOrderStatus(Request $request, Command $command): Response
    {
        $newStatus = $request->request->get('status');
        $command->setStatus($newStatus);

        $this->entityManager->flush();

        return $this->redirectToRoute('employee_orders');
    }
}