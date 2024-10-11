<?php

namespace App\Controller;

use App\Document\StatistiqueVente;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends AbstractController
{
    private $documentManager;

    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
    }

    public function index(): Response
    {
        // Récupérer les statistiques depuis MongoDB
        $statistiques = $this->documentManager->getRepository(StatistiqueVente::class)->findAll();

        return $this->render('dashboard/index.html.twig', [
            'statistiques' => $statistiques,
        ]);
    }
}