<?php

namespace App\Repository;

use App\Entity\LockProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LockProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockProperty[]    findAll()
 * @method LockProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockPropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LockProperty::class);
    }

    // /**
    //  * @return LockProperty[] Returns an array of LockProperty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LockProperty
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByName($value): ?LockProperty
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.name = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
