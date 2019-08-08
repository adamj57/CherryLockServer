<?php

namespace App\Repository;

use App\Entity\RegisterCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RegisterCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegisterCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegisterCode[]    findAll()
 * @method RegisterCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegisterCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RegisterCode::class);
    }

    // /**
    //  * @return RegisterCode[] Returns an array of RegisterCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegisterCode
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByCode($value): ?RegisterCode
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.code = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
