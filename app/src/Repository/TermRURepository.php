<?php

namespace App\Repository;

use App\Entity\TermRU;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TermRU>
 *
 * @method TermRU|null find($id, $lockMode = null, $lockVersion = null)
 * @method TermRU|null findOneBy(array $criteria, array $orderBy = null)
 * @method TermRU[]    findAll()
 * @method TermRU[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TermRURepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TermRU::class);
    }
}
