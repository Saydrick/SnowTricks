<?php

namespace App\Repository;

use App\Entity\TypesMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypesMedia>
 *
 * @method TypesMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypesMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypesMedia[]    findAll()
 * @method TypesMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypesMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypesMedia::class);
    }

    //    /**
    //     * @return TypesMedia[] Returns an array of TypesMedia objects
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

    //    public function findOneBySomeField($value): ?TypesMedia
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
