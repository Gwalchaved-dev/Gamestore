<?php

namespace App\Repository;

use App\Entity\JeuxVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JeuxVideos>
 */
class JeuxVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JeuxVideos::class);
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

    /**
     * Récupérer tous les jeux pour les afficher dans le stock.
     */
    public function findAllJeux()
    {
        return $this->createQueryBuilder('j')
            ->orderBy('j.titre', 'ASC') // Trie les jeux par titre
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupérer un jeu par son ID.
     */
    public function findById($id)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
