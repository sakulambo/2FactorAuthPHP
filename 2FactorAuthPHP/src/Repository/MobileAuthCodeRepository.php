<?php

namespace App\Repository;

use App\Entity\MobileAuthCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MobileAuthCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobileAuthCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobileAuthCode[]    findAll()
 * @method MobileAuthCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileAuthCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobileAuthCode::class);
    }

    // /**
    //  * @return MobileAuthCode[] Returns an array of MobileAuthCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MobileAuthCode
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
