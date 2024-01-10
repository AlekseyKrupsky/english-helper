<?php

namespace App\Repository;

use App\Entity\TermEN;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TermEN>
 *
 * @method TermEN|null find($id, $lockMode = null, $lockVersion = null)
 * @method TermEN|null findOneBy(array $criteria, array $orderBy = null)
 * @method TermEN[]    findAll()
 * @method TermEN[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermENRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermEN::class);
    }

    public function getTermsWithTranslations(string $translationType): array
    {
        return $this->createQueryBuilder('t')->getQuery()->getResult();
    }

//    /**
//     * @return TermEN[] Returns an array of TermEN objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TermEN
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
