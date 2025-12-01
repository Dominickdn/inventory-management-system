<?php

namespace App\Repository;

use App\Entity\AssignedItems;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AssignedItems>
 */
class AssignedItemsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AssignedItems::class);
    }

    public function findAllOrdered(): QueryBuilder
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'ASC');
    }

    public function findByUserOrderedQB(int $userId, string $orderByField = 'id', string $order = 'ASC'): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.userId = :userId')  // use the entity property
            ->setParameter('userId', $userId)
            ->orderBy('a.assignedAt', $order);
    }

    //    /**
    //     * @return AssignedItems[] Returns an array of AssignedItems objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?AssignedItems
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
