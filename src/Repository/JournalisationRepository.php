<?php

namespace App\Repository;

use App\Entity\Journalisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Journalisation>
 *
 * @method Journalisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Journalisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Journalisation[]    findAll()
 * @method Journalisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JournalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Journalisation::class);
    }

//    /**
//     * @return Journalisation[] Returns an array of Journalisation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Journalisation
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
