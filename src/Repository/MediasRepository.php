<?php

namespace App\Repository;

use App\Entity\Medias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Medias>
 *
 * @method Medias|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medias|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medias[]    findAll()
 * @method Medias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medias::class);
    }

    //    /**
    //     * @return Medias[] Returns an array of Medias objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

       public function findOneByTrick($value): ?Medias
       {
           return $this->createQueryBuilder('m')
               ->andWhere('m.trick = :val')
               ->andWhere('m.type_media = 1')
               ->setParameter('val', $value)
               ->orderBy('m.path')
               ->setMaxResults(1)
               ->getQuery()
               ->getOneOrNullResult()
           ;
       }

       public function findAllMediasForTrickExceptFirst($value): array
       {
            return $this->createQueryBuilder('m')
                ->andWhere('m.trick = :val')
                ->setParameter('val', $value)
                ->orderBy('m.id', 'ASC')
                ->setFirstResult(1)
                ->getQuery()
                ->getResult()
            ;
       }
}
