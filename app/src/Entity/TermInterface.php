<?php

namespace App\Entity;

use App\Enum\TermType;
use Doctrine\Common\Collections\Collection;

interface TermInterface
{
    public function getTerm(): ?string;
    public function getType(): string;
    public function addTranslation(TermType $type, TermInterface $term): static;
    public function removeTranslation(TermType $type, TermInterface $term): static;
    public function getTranslations(TermType|string $type): ?Collection;
    public function isLearned(): bool;
    public function setLearned(bool $learned): static;
}
