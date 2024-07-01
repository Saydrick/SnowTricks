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

    public function findOneByLabel(string $label): ?TypesMedia
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.label = :val')
            ->setParameter('val', $label)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
        ;
    }
}
