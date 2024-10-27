<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Command;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;

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
            // Vérification de l'existence de l'entité Game pour éviter les erreurs
            $game = $command->getGame();
            if (!$game) {
                continue; // Si aucun jeu n'est associé, on passe à la commande suivante
            }

            // Assure que saleDate est défini; utilise la date actuelle par défaut
            $saleDate = $command->getDate() ?? new \DateTime();

            // Synchronisation pour GameSales
            $gameSale = new GameSales(
                $game->getId(),         // gameId
                $saleDate               // saleDate
            );
            $gameSale->setCopiesSold($command->getQuantity() ?? 0);
            $gameSale->setPricePerCopy($game->getPrice() ?? 0.0);
            $this->dm->persist($gameSale);

            // Synchronisation pour AgencySales
            $agency = $command->getAgence();
            if ($agency) { // Vérifie si l'agence existe
                $agencySale = new AgencySales(
                    $agency->getId(),   // agencyId
                    $saleDate           // saleDate
                );
                $agencySale->setCopiesSold($command->getQuantity() ?? 0);
                $agencySale->setPricePerCopy($game->getPrice() ?? 0.0);
                $this->dm->persist($agencySale);
            }

            // Synchronisation pour GenreSales
            $genre = $game->getGenre();
            if ($genre) {
                $genreSale = new GenreSales(
                    $genre,             // genre
                    $saleDate           // saleDate
                );
                $genreSale->setCopiesSold($command->getQuantity() ?? 0);
                $genreSale->setPricePerCopy($game->getPrice() ?? 0.0);
                $this->dm->persist($genreSale);
            }
        }

        $this->dm->flush();
    }
}