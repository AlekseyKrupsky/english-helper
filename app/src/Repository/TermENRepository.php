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
}
