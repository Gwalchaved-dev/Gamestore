<?php

namespace App\service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Command;
use App\Entity\Agence;
use App\Entity\JeuxVideos;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use App\Repository\JeuxVideosRepository;
use Psr\Log\LoggerInterface;
use Exception;

class CommandSyncService
{
    private EntityManagerInterface $entityManager;
    private DocumentManager $dm;
    private JeuxVideosRepository $jeuxRepository;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentManager $dm,
        JeuxVideosRepository $jeuxRepository,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->dm = $dm;
        $this->jeuxRepository = $jeuxRepository;
        $this->logger = $logger;
    }

    // Méthode pour synchroniser les commandes validées
    public function syncValidatedCommands(): void
    {
        try {
            $validatedCommands = $this->entityManager->getRepository(Command::class)->findBy(['status' => 'validé']);
        
            foreach ($validatedCommands as $command) {
                foreach ($command->getGames() as $game) {
                    // Synchronisation des ventes par jeu
                    $existingGameSale = $this->dm->getRepository(GameSales::class)->findOneBy(['gameId' => $game->getId()]);
                    if (!$existingGameSale) {
                        $newGameSale = new GameSales($game->getId(), new \DateTime());
                        $newGameSale->setGenre($game->getGenre() ?? ''); 
                        $newGameSale->setCopiesSold(1); 
                        $this->dm->persist($newGameSale);
                        $this->logger->info("GameSale ajouté pour le jeu ID: {$game->getId()} avec 1 copie.");
                    } else {
                        $existingGameSale->setCopiesSold($existingGameSale->getCopiesSold() + 1);
                        $this->logger->info("GameSale mis à jour pour le jeu ID: {$game->getId()} avec 1 copie supplémentaire.");
                    }
        
                    // Synchronisation des ventes par genre
                    if ($game->getGenre()) {
                        $existingGenreSale = $this->dm->getRepository(GenreSales::class)->findOneBy(['genre' => $game->getGenre()]);
                        if (!$existingGenreSale) {
                            $newGenreSale = new GenreSales($game->getGenre(), new \DateTime());
                            $newGenreSale->setCopiesSold(1);
                            $this->dm->persist($newGenreSale);
                            $this->logger->info("GenreSale ajouté pour le genre: {$game->getGenre()} avec 1 copie.");
                        } else {
                            $existingGenreSale->setCopiesSold($existingGenreSale->getCopiesSold() + 1);
                            $this->logger->info("GenreSale mis à jour pour le genre: {$game->getGenre()} avec 1 copie supplémentaire.");
                        }
                    }
                }
        
                // Synchronisation des ventes par agence
                $agency = $command->getAgence();
                if ($agency) {
                    $existingAgencySale = $this->dm->getRepository(AgencySales::class)->findOneBy(['agencyId' => $agency->getId()]);
                    if (!$existingAgencySale) {
                        $newAgencySale = new AgencySales($agency->getId(), new \DateTime());
                        $newAgencySale->setCopiesSold(1);
                        $newAgencySale->setNom($agency->getNom());
                        $this->dm->persist($newAgencySale);
                        $this->logger->info("AgencySale ajouté pour l'agence ID: {$agency->getId()} avec 1 copie.");
                    } else {
                        $existingAgencySale->setCopiesSold($existingAgencySale->getCopiesSold() + 1);
                        $this->logger->info("AgencySale mis à jour pour l'agence ID: {$agency->getId()} avec 1 copie supplémentaire.");
                    }
                }
            }
        
            // Enregistrement des changements dans MongoDB
            $this->dm->flush();
            $this->logger->info("Synchronisation des ventes terminée.");
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la synchronisation des commandes : " . $e->getMessage());
        }
    }
}