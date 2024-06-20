<?php

namespace App\Repository;

use App\Entity\TrickGroups;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TrickGroups>
 *
 * @method TrickGroups|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrickGroups|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrickGroups[]    findAll()
 * @method TrickGroups[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickGroupsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TrickGroups::class);
    }
}
