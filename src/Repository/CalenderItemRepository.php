<?php

namespace App\Repository;

use App\Entity\CalenderItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CalenderItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method CalenderItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method CalenderItem[]    findAll()
 * @method CalenderItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalenderItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CalenderItem::class);
    }

    // /**
    //  * @return CalenderItem[] Returns an array of CalenderItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CalenderItem
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
