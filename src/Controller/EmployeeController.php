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

#[Route('/employee')]
#[IsGranted('ROLE_EMPLOYEE')]
class EmployeeController extends AbstractController
{
    #[Route('/', name: 'app_employee')]
    public function employeeSpace(): Response
    {
        return $this->render('Employee/employee_space.html.twig');
    }

    #[Route('/dashboard', name: 'employee_dashboard')]
    public function dashboard(DocumentManager $dm, JeuxVideosRepository $jeuxRepository, Request $request): Response
    {
        $filterType = $request->get('filter_type', 'game');
        $filterValue = $request->get('filter_value', null);

        $salesData = [];
        $salesDates = [];
        $games = [];
        $agencies = [];
        $genres = [];

        // Fetch games for filtering
        if ($filterType === 'game') {
            $games = $jeuxRepository->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(GameSales::class)->findBy(['gameId' => $filterValue]);
            }
        }
        // Fetch agencies for filtering
        elseif ($filterType === 'agency') {
            $agencies = $dm->getRepository(AgencySales::class)->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(AgencySales::class)->findBy(['agencyId' => $filterValue]);
            }
        }
        // Fetch genres for filtering
        elseif ($filterType === 'genre') {
            $genres = $dm->getRepository(GenreSales::class)->findAll();
            if ($filterValue) {
                $salesData = $dm->getRepository(GenreSales::class)->findBy(['genre' => $filterValue]);
            }
        }

        // Extract sales dates for the chart
        foreach ($salesData as $sale) {
            if ($sale->getSaleDate() !== null) {
                $salesDates[] = $sale->getSaleDate()->format('Y-m-d');
            }
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
    public function orders(): Response
    {
        return $this->render('Employee/employee_orders.html.twig');
    }
}
