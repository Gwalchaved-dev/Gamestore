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
            ->getScalarResult(); // Renvoie uniquement les genres comme des chaînes de caractères
    }

    /**
     * Récupérer les jeux en fonction des filtres de genre et de prix.
     */
    public function findByFilters($genre, $minPrice, $maxPrice)
    {
        $qb = $this->createQueryBuilder('j');

        if ($genre) {
            $qb->andWhere('j.genre = :genre')
               ->setParameter('genre', $genre);
        }

        if ($minPrice) {
            $qb->andWhere('j.prix >= :minPrice')
               ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $qb->andWhere('j.prix <= :maxPrice')
               ->setParameter('maxPrice', $maxPrice);
        }

        return $qb->getQuery()->getResult();
    }
}
