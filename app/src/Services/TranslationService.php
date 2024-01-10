<?php

namespace App\Services;

use ApiPlatform\Api\IriConverterInterface;
use App\Entity\TermEN;
use App\Entity\TermInterface;
use App\Entity\TermRU;
use Doctrine\ORM\EntityManagerInterface;

class TranslationService
{
    protected const CLASS_METHOD_ADD_MAP = [
        TermRU::class => 'addRussianTranslation',
        TermEN::class => 'addEnglishTranslation',
    ];

    protected const CLASS_METHOD_REMOVE_MAP = [
        TermRU::class => 'removeRussianTranslation',
        TermEN::class => 'removeEnglishTranslation',
    ];

    private IriConverterInterface $iriConverter;
    private EntityManagerInterface $entityManager;

    public function __construct(
        IriConverterInterface $iriConverter,
        EntityManagerInterface $entityManager,
    )
    {
        $this->iriConverter = $iriConverter;
        $this->entityManager = $entityManager;
    }

    public function addTranslations(TermInterface $term, array $translations): void
    {
        $termType = get_class($term);

        foreach ($translations as $iri) {
            $translationTerm = $this->iriConverter->getResourceFromIri($iri);
            $translationTermType = get_class($translationTerm);

            if ($termType === $translationTermType) {
                throw new \Exception('ERROR 101');
            }

            $methodName = self::CLASS_METHOD_ADD_MAP[$translationTermType] ?? null;

            if (!$methodName) {
                throw new \Exception('ERROR 102');
            }

            $term->{$methodName}($translationTerm);
        }

        $this->entityManager->flush();
    }

    public function removeTranslations(TermInterface $term, array $translations): void
    {
        foreach ($translations as $iri) {
            $translationTerm = $this->iriConverter->getResourceFromIri($iri);
            $translationTermType = get_class($translationTerm);

            $methodName = self::CLASS_METHOD_REMOVE_MAP[$translationTermType] ?? null;

            if (!$methodName) {
                throw new \Exception('ERROR 102');
            }

            $term->{$methodName}($translationTerm);
        }

        $this->entityManager->flush();
    }
}
