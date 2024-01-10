<?php

namespace App\Repository;

use App\Entity\IgnoreTerm;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<IgnoreTerm>
 *
 * @method IgnoreTerm|null find($id, $lockMode = null, $lockVersion = null)
 * @method IgnoreTerm|null findOneBy(array $criteria, array $orderBy = null)
 * @method IgnoreTerm[]    findAll()
 * @method IgnoreTerm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IgnoreTermRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IgnoreTerm::class);
    }
}
