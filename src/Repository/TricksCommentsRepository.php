<?php

namespace App\Repository;

use App\Entity\TricksComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TricksComments>
 *
 * @method TricksComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method TricksComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method TricksComments[]    findAll()
 * @method TricksComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TricksComments::class);
    }

    //    /**
    //     * @return TricksComments[] Returns an array of TricksComments objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TricksComments
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
