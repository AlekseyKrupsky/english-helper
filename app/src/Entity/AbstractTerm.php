<?php

namespace App\Entity;

use App\Enum\Lang;
use Doctrine\Common\Collections\Collection;

abstract class AbstractTerm implements TermInterface
{
    protected const CLASS_METHOD_ADD_MAP = [
        TermRU::class => 'addRussianTranslation',
        TermEN::class => 'addEnglishTranslation',
    ];

    protected const CLASS_METHOD_REMOVE_MAP = [
        TermRU::class => 'removeRussianTranslation',
        TermEN::class => 'removeEnglishTranslation',
    ];

//abstract public function addTranslation(TermType $translationtype, TermInterface $term): static
//    {
//
//    }

//    abstract public function removeTranslation(TermType $type, TermInterface $term): static;

    public function getTranslations(Lang|string $type): ?Collection
    {
        if (is_string($type)) {
            $type = Lang::tryFrom($type);
        }

        if ($this->getType() === $type->value) {
            throw new \Exception('ERROR 105');
        }

        $methodName = match($type) {
            Lang::EN => 'getEnglishTranslations',
            Lang::RU => 'getRussianTranslations',
        };

        return $this->$methodName();
    }
}
