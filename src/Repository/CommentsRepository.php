<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\Tricks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comments>
 *
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

       /**
        * @return Comments[] Returns an array of Comments objects
        */
    public function findByRecentComments(Tricks $trick): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.message', 'c.updatedAt', 'u.username', 'u.photo')
            ->join('c.user', 'u')
            ->where('c.trick = :trick')
            ->setParameter('trick', $trick)
            ->orderBy('c.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
