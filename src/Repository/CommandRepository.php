<?php

namespace App\Repository;

use App\Entity\Command;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Command::class);
    }

    /**
     * Récupère toutes les commandes d'un utilisateur spécifique, triées par date de création.
     *
     * @param User $user
     * @param string|null $status Filtre optionnel pour le statut de la commande (par exemple, 'New', 'En attente', etc.)
     * @return Command[]
     */
    public function findUserCommands(User $user, ?string $status = null): array
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.date', 'DESC'); // Trie par date décroissante

        if ($status !== null) {
            $queryBuilder->andWhere('c.status = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    // Ajoutez d'autres méthodes personnalisées si nécessaire
}