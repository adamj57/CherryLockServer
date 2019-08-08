<?php

namespace App\Repository;

use App\Entity\LocalOpen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LocalOpen|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocalOpen|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocalOpen[]    findAll()
 * @method LocalOpen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalOpenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LocalOpen::class);
    }

    // /**
    //  * @return LocalOpening[] Returns an array of LocalOpening objects
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
    public function findOneBySomeField($value): ?LocalOpening
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
