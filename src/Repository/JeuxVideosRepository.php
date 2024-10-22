<?php

namespace App\Repository;

use App\Entity\JeuxVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class JeuxVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JeuxVideos::class);
    }

    /**
     * Récupérer les genres distincts à partir des jeux vidéos.
     */
    public function findDistinctGenres()
    {
        return $this->createQueryBuilder('j')
            ->select('DISTINCT j.genre')
            ->getQuery()
            ->getScalarResult(); // Renvoie uniquement les genres sous forme de chaînes
    }

    /**
     * Récupérer les jeux en fonction des filtres de genre et de prix.
     */
    public function findByFilters($genre, $prix)
    {
        $qb = $this->createQueryBuilder('j');

        if ($genre) {
            $qb->andWhere('j.genre = :genre')
               ->setParameter('genre', $genre);
        }

        // Gestion des filtres de prix
        if ($prix) {
            switch ($prix) {
                case 'moins-20':
                    $qb->andWhere('j.prix < :maxPrice')
                       ->setParameter('maxPrice', 20);
                    break;
                case '20-50':
                    $qb->andWhere('j.prix >= :minPrice')
                       ->setParameter('minPrice', 20)
                       ->andWhere('j.prix <= :maxPrice')
                       ->setParameter('maxPrice', 50);
                    break;
                case 'plus-50':
                    $qb->andWhere('j.prix > :minPrice')
                       ->setParameter('minPrice', 50);
                    break;
            }
        }

        return $qb->getQuery()->getResult();
    }
}
