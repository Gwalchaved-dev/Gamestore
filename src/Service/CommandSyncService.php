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
            $saleDate = $command->getDate() ?? new \DateTime(); // Définit une date par défaut

            // Synchronisation pour GameSales
            $gameSale = new GameSales(
                $command->getGame()->getId(),         // gameId
                $saleDate                             // saleDate
            );
            $gameSale->setCopiesSold($command->getQuantity());
            $gameSale->setPricePerCopy($command->getGame()->getPrice());
            $this->dm->persist($gameSale);

            // Synchronisation pour AgencySales
            $agencySale = new AgencySales(
                $command->getAgence()->getId(),       // agencyId
                $saleDate                             // saleDate
            );
            $agencySale->setCopiesSold($command->getQuantity());
            $agencySale->setPricePerCopy($command->getGame()->getPrice());
            $this->dm->persist($agencySale);

            // Synchronisation pour GenreSales
            $genre = $command->getGame()->getGenre();
            if ($genre) {
                $genreSale = new GenreSales(
                    $genre,                         // genre
                    $saleDate                       // saleDate
                );
                $genreSale->setCopiesSold($command->getQuantity());
                $genreSale->setPricePerCopy($command->getGame()->getPrice());
                $this->dm->persist($genreSale);
            }
        }

        $this->dm->flush();
    }
}