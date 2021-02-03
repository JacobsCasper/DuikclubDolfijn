<?php

namespace App\Repository;

use App\Entity\WebFormRadioType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebFormRadioType|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebFormRadioType|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebFormRadioType[]    findAll()
 * @method WebFormRadioType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebFormRadioTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebFormRadioType::class);
    }

    // /**
    //  * @return WebFormRadioType[] Returns an array of WebFormRadioType objects
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
    public function findOneBySomeField($value): ?WebFormRadioType
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
