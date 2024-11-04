<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    #[Route('/admin/sales_dashboard', name: 'sales_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, DocumentManager $documentManager, Request $request): Response
    {
        // Filtrer les données de vente
        $filterType = $request->query->get('filter_type', 'game');
        $filterValue = $request->query->get('filter_value', '');

        $salesData = [];
        if ($filterType === 'game') {
            $salesData = $documentManager->getRepository('App\Document\GameSales')->findBy(['gameId' => $filterValue]);
        } elseif ($filterType === 'agency') {
            $salesData = $documentManager->getRepository('App\Document\AgencySales')->findBy(['agencyId' => $filterValue]);
        } elseif ($filterType === 'genre') {
            $salesData = $documentManager->getRepository('App\Document\GenreSales')->findBy(['genre' => $filterValue]);
        }

        $totalSales = 0;
        $totalRevenue = 0;
        $salesDates = [];
        $salesCounts = [];

        foreach ($salesData as $sale) {
            $totalSales += $sale->getCopiesSold();
            $totalRevenue += $sale->getTotalRevenue();
            $salesDates[] = $sale->getSaleDate()->format('Y-m-d');
            $salesCounts[] = $sale->getCopiesSold();
        }

        return $this->render('admin/sales_dashboard.html.twig', [
            'sales_dates' => $salesDates,
            'sales_counts' => $salesCounts,
            'totalSales' => $totalSales,
            'totalRevenue' => $totalRevenue,
            'filter_type' => $filterType,
            'filter_value' => $filterValue,
        ]);
    }
}
