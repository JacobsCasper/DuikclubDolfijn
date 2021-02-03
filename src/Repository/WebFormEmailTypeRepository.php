<?php

namespace App\Repository;

use App\Entity\WebFormEmailType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebFormEmailType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebFormEmailType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebFormEmailType[]    findAll()
 * @method WebFormEmailType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebFormEmailTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebFormEmailType::class);
    }

    // /**
    //  * @return WebFormEmailType[] Returns an array of WebFormEmailType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebFormEmailType
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
