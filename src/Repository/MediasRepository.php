<?php

namespace App\Repository;

use App\Entity\Medias;
use App\Entity\Tricks;
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

    public function findOneByID(int $id): ?Medias
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.id = :val')
            ->setParameter('val', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByTrick(Tricks $value): ?Medias
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

    public function findAllByTrick(Tricks $value): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.trick = :val')
            ->setParameter('val', $value)
            ->orderBy('m.path')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllMediasForTrickExceptFirst(Tricks $value): array
    {
         return $this->createQueryBuilder('m')
             ->andWhere('m.trick = :val')
             ->setParameter('val', $value)
             ->orderBy('m.id', 'ASC')
             ->getQuery()
             ->getResult()
         ;
    }

    public function findLastPathByTrick(Tricks $trick): string
    {
        try {
              return (string) $this->createQueryBuilder('m')
                  ->select('m.path')
                  ->andWhere('m.trick = :val')
                  ->setParameter('val', $trick)
                  ->orderBy('m.path', 'DESC')
                  ->setMaxResults(1)
                  ->getQuery()
                  ->getSingleScalarResult()
              ;
        } catch (\Doctrine\ORM\NoResultException $e) {
            return 'none';
        }
    }
}
