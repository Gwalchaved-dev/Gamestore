<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Entity\Command;
use App\Document\GameSales;
use App\Document\AgencySales;
use App\Document\GenreSales;
use Psr\Log\LoggerInterface;
use Exception;

class CommandSyncService
{
    private EntityManagerInterface $entityManager;
    private DocumentManager $dm;
    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        DocumentManager $dm,
        LoggerInterface $logger
    ) {
        $this->entityManager = $entityManager;
        $this->dm = $dm;
        $this->logger = $logger;
    }

    // Méthode pour synchroniser les commandes validées
    public function syncValidatedCommands(): void
    {
        try {
            // Récupérer les commandes validées depuis MySQL
            $validatedCommands = $this->entityManager->getRepository(Command::class)->findBy(['status' => 'validé']);
        
            foreach ($validatedCommands as $command) {
                // Log de début de synchronisation pour chaque commande
                $this->logger->debug("Début de synchronisation pour la commande ID: {$command->getId()}.");

                // Récupération des jeux et quantités depuis la commande
                foreach ($command->getGameTitlesAndQuantities() as $gameData) {
                    $gameTitle = $gameData['titre'];
                    $genre = $gameData['genre'] ?? '';
                    $copiesSold = $gameData['quantite'];

                    // Vérifier si les données de jeu sont valides
                    if ($gameTitle && $copiesSold > 0) {
                        // Synchronisation des ventes par jeu
                        $existingGameSale = $this->dm->getRepository(GameSales::class)->findOneBy(['gameTitle' => $gameTitle]);
                        if (!$existingGameSale) {
                            $newGameSale = new GameSales($gameTitle, new \DateTime());
                            $newGameSale->setGenre($genre);
                            $newGameSale->setCopiesSold($copiesSold);
                            $this->dm->persist($newGameSale);
                            $this->logger->info("Ajout de GameSale pour le jeu : {$gameTitle} avec {$copiesSold} copies.");
                        } else {
                            $existingGameSale->setCopiesSold($existingGameSale->getCopiesSold() + $copiesSold);
                            $this->logger->info("Mise à jour de GameSale pour le jeu : {$gameTitle} avec {$copiesSold} copies supplémentaires.");
                        }

                        // Synchronisation des ventes par genre
                        if ($genre) {
                            $existingGenreSale = $this->dm->getRepository(GenreSales::class)->findOneBy(['genre' => $genre]);
                            if (!$existingGenreSale) {
                                $newGenreSale = new GenreSales($genre, new \DateTime());
                                $newGenreSale->setCopiesSold($copiesSold);
                                $this->dm->persist($newGenreSale);
                                $this->logger->info("Ajout de GenreSale pour le genre : {$genre} avec {$copiesSold} copies.");
                            } else {
                                $existingGenreSale->setCopiesSold($existingGenreSale->getCopiesSold() + $copiesSold);
                                $this->logger->info("Mise à jour de GenreSale pour le genre : {$genre} avec {$copiesSold} copies supplémentaires.");
                            }
                        }
                    } else {
                        $this->logger->warning("Données de jeu non valides pour la commande ID: {$command->getId()}.");
                    }
                }

                // Synchronisation des ventes par agence
                $agency = $command->getAgence();
                if ($agency) {
                    $existingAgencySale = $this->dm->getRepository(AgencySales::class)->findOneBy(['agencyId' => $agency->getId()]);
                    if (!$existingAgencySale) {
                        $newAgencySale = new AgencySales($agency->getId(), new \DateTime());
                        $newAgencySale->setCopiesSold(1); // Ajout d'une copie pour chaque commande
                        $newAgencySale->setNom($agency->getNom());
                        $this->dm->persist($newAgencySale);
                        $this->logger->info("Ajout de AgencySale pour l'agence ID: {$agency->getId()} avec 1 copie.");
                    } else {
                        $existingAgencySale->setCopiesSold($existingAgencySale->getCopiesSold() + 1);
                        $this->logger->info("Mise à jour de AgencySale pour l'agence ID: {$agency->getId()} avec 1 copie supplémentaire.");
                    }
                } else {
                    $this->logger->warning("Agence non définie pour la commande ID: {$command->getId()}.");
                }
            }
        
            // Sauvegarder les changements dans MongoDB
            $this->dm->flush();
            $this->logger->info("Synchronisation des ventes terminée avec succès.");
        } catch (Exception $e) {
            $this->logger->error("Erreur lors de la synchronisation des commandes : " . $e->getMessage());
        }
    }
}