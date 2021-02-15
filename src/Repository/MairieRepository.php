<?php

namespace App\Repository;

use App\Entity\Mairie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Mairie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mairie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mairie[]    findAll()
 * @method Mairie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MairieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mairie::class);
    }

    // /**
    //  * @return Mairie[] Returns an array of Mairie objects
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

    public function findOneByMail($value): ?Mairie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.mail = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
