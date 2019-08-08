<?php

namespace App\Repository;

use App\Entity\DeviceToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceToken[]    findAll()
 * @method DeviceToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceTokenRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviceToken::class);
    }

    // /**
    //  * @return DeviceToken[] Returns an array of DeviceToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeviceToken
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByToken($value): ?DeviceToken
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.token = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
