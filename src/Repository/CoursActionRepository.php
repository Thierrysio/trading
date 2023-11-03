<?php

namespace App\Repository;

use App\Entity\CoursAction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CoursAction>
 *
 * @method CoursAction|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoursAction|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoursAction[]    findAll()
 * @method CoursAction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoursActionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoursAction::class);
    }

//    /**
//     * @return CoursAction[] Returns an array of CoursAction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CoursAction
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
