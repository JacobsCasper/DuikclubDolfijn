<?php

namespace App\Repository;

use App\Entity\WebFormElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebFormElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebFormElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebFormElement[]    findAll()
 * @method WebFormElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebFormElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebFormElement::class);
    }

    // /**
    //  * @return WebFormElement[] Returns an array of WebFormElement objects
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
    public function findOneBySomeField($value): ?WebFormElement
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
