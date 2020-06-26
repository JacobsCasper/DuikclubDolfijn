<?php

namespace App\Repository;

use App\Entity\NieuwsItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NieuwsItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method NieuwsItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method NieuwsItem[]    findAll()
 * @method NieuwsItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NieuwsItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NieuwsItem::class);
    }

    // /**
    //  * @return NieuwsItem[] Returns an array of NieuwsItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NieuwsItem
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
