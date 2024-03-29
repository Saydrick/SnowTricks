<?php

namespace App\Repository;

use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tricks>
 *
 * @method Tricks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricks[]    findAll()
 * @method Tricks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricks::class);
    }

       /**
        * @return Tricks[] Returns an array of Tricks objects
        */
       public function findByRecentTricks(): array
       {
           return $this->createQueryBuilder('t')
               ->orderBy('t.updatedAt', 'DESC')
               ->getQuery()
               ->getResult()
           ;
       }


       public function paginateTricks(int $limit): Paginator
       {
            return new Paginator($this
                ->createQueryBuilder('t')
                ->orderBy('t.updatedAt', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
            );
       }
}
