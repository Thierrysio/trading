<?php

namespace App\Repository;

use App\Entity\MotDePasse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MotDePasse>
 *
 * @method MotDePasse|null find($id, $lockMode = null, $lockVersion = null)
 * @method MotDePasse|null findOneBy(array $criteria, array $orderBy = null)
 * @method MotDePasse[]    findAll()
 * @method MotDePasse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotDePasseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MotDePasse::class);
    }

//    /**
//     * @return MotDePasse[] Returns an array of MotDePasse objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?MotDePasse
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
