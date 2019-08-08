<?php

namespace App\Repository;

use App\Entity\LockRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LockRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method LockRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method LockRequest[]    findAll()
 * @method LockRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LockRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LockRequest::class);
    }

    // /**
    //  * @return LockRequest[] Returns an array of LockRequest objects
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
    public function findOneBySomeField($value): ?LockRequest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function isAnyRequestPending(string $requestType): bool
    {
        $result = $this->createQueryBuilder("l")
            ->andWhere("l.type = :type")
            ->andWhere("l.status = :created OR l.status = :pending")
            ->setParameter("type", $requestType)
            ->setParameter("created", LockRequest::STATUS_CREATED)
            ->setParameter("pending", LockRequest::STATUS_PENDING)
            ->getQuery()
            ->getResult();
        return count($result) > 0;
    }

    public function findOneByStatus(string $requestStatus): ?LockRequest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.status = :val')
            ->orderBy("l.timeUpdated", "ASC")
            ->setParameter('val', $requestStatus)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findOneByID($id): ?LockRequest
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
