<?php

namespace App\Command;

use App\Service\CommandSyncService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Début de la synchronisation des ventes...");

        // Exécuter la synchronisation des commandes validées
        $this->syncService->syncValidatedCommands();

        $output->writeln("Synchronisation des ventes terminée avec succès.");
        return Command::SUCCESS;
    }
}