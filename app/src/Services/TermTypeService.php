<?php

namespace App\Services;

use App\Entity\TermEN;
use App\Entity\TermRU;
use App\Enum\Lang;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TermTypeService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEntityClassByType(Lang $type): string
    {
        return match($type) {
            Lang::EN => TermEN::class,
            Lang::RU => TermRU::class,
        };
    }

    public function getRepositoryByType(Lang $type): EntityRepository
    {
        return $this->entityManager->getRepository($this->getEntityClassByType($type));
    }
}
