<?php

namespace App\Services;

use App\Entity\TermEN;
use App\Entity\TermRU;
use App\Enum\TermType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TermTypeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityClassByType(TermType $type): string
    {
        return match($type) {
            TermType::EN => TermEN::class,
            TermType::RU => TermRU::class,
        };
    }

    public function getRepositoryByType(TermType $type): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityClassByType($type));
    }
}
