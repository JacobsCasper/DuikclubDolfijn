<?php

namespace App\Repository;

use App\Entity\WebFormIntType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebFormIntType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebFormIntType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebFormIntType[]    findAll()
 * @method WebFormIntType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebFormIntTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebFormIntType::class);
    }

    // /**
    //  * @return WebFormIntType[] Returns an array of WebFormIntType objects
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
    public function findOneBySomeField($value): ?WebFormIntType
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
