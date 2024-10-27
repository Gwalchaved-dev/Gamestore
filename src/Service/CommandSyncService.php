<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Command;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use App\Entity\CartJeuxVideos;

class CommandSyncService
{
    private EntityManagerInterface $entityManager;
    private DocumentManager $dm;

    public function __construct(EntityManagerInterface $entityManager, DocumentManager $dm)
    {
        $this->entityManager = $entityManager;
        $this->dm = $dm;
    }

    public function syncValidatedCommands(): void
    {
        // Récupère les commandes validées dans MySQL
        $validatedCommands = $this->entityManager->getRepository(Command::class)->findBy(['status' => 'validé']);

        foreach ($validatedCommands as $command) {
            foreach ($command->getCartJeuxVideos() as $cartJeuxVideo) {
                $game = $cartJeuxVideo->getJeuxVideo();

                if ($game) {
                    // Exemple de synchronisation pour GameSales
                    $gameSale = new GameSales();
                    $gameSale->setGameId($game->getId());
                    $gameSale->setSaleDate($command->getDate());
                    $gameSale->setCopiesSold($cartJeuxVideo->getQuantite());

                    // Ajout de l'agence
                    if ($command->getAgence()) {
                        $agencySale = new AgencySales();
                        $agencySale->setAgencyId($command->getAgence()->getId());
                        $agencySale->setSaleDate($command->getDate());
                        $agencySale->setCopiesSold($cartJeuxVideo->getQuantite());
                        $this->dm->persist($agencySale);
                    }

                    // Ajout du genre si disponible
                    if ($game->getGenre()) {
                        $genreSale = new GenreSales();
                        $genreSale->setGenre($game->getGenre());
                        $genreSale->setSaleDate($command->getDate());
                        $genreSale->setCopiesSold($cartJeuxVideo->getQuantite());
                        $this->dm->persist($genreSale);
                    }

                    // Persist le document GameSales
                    $this->dm->persist($gameSale);
                }
            }
        }

        // Enregistre toutes les modifications dans MongoDB
        $this->dm->flush();
    }
}