<?php

namespace App\Repository;

use App\Entity\WebForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebForm[]    findAll()
 * @method WebForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebForm::class);
    }

    // /**
    //  * @return WebForm[] Returns an array of WebForm objects
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
    public function findOneBySomeField($value): ?WebForm
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
