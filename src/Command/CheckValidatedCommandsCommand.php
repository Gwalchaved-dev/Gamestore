<?php

namespace App\Command;

use App\Entity\Command;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:check-validated-commands')]
class CheckValidatedCommandsCommand extends BaseCommand
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $validatedCommands = $this->entityManager->getRepository(Command::class)->findBy(['status' => 'validé']);
        
        foreach ($validatedCommands as $command) {
            $output->writeln("Commande ID: {$command->getId()}");
            $output->writeln("Détails des jeux et quantités :");
            dump($command->getGamesTitlesAndQuantities());
        }

        return BaseCommand::SUCCESS;
    }
}