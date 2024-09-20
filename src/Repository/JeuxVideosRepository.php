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
    //    /**
    //     * @return JeuxVideos[] Returns an array of JeuxVideos objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('j.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?JeuxVideos
    //    {
    //        return $this->createQueryBuilder('j')
    //            ->andWhere('j.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
