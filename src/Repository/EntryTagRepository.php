<?php

namespace App\Repository;

use App\Entity\EntryTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method EntryTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method EntryTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method EntryTag[]    findAll()
 * @method EntryTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntryTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, EntryTag::class);
    }

    // /**
    //  * @return EntryTag[] Returns an array of EntryTag objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EntryTag
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOneByTagID($value): ?EntryTag
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.tagID = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findByActiveness($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.active = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
            ;
    }

}
