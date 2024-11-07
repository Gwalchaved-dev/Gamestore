<?php

namespace App\Command;

use App\Service\CommandSyncService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-sales',
    description: 'Synchronise les ventes validées de MySQL vers MongoDB.'
)]
class SyncSalesCommand extends Command
{
    private CommandSyncService $syncService;

    public function __construct(CommandSyncService $syncService)
    {
        $this->syncService = $syncService;
        parent::__construct();
    }

    protected function configure(): void
    {
        // Ajout de la configuration si besoin (arguments, options)
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title("Synchronisation des ventes");

        try {
            // Exécuter la synchronisation des commandes validées
            $this->syncService->syncValidatedCommands();
            $io->success("Synchronisation des ventes terminée avec succès.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error("Erreur lors de la synchronisation : " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}