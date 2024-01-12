<?php

namespace App\Entity;

use App\Enum\Lang;
use Doctrine\Common\Collections\Collection;

interface TermInterface
{
    public function getTerm(): ?string;
    public function getType(): string;
    public function addTranslation(Lang $type, TermInterface $term): static;
    public function removeTranslation(Lang $type, TermInterface $term): static;
    public function getTranslations(Lang|string $type): ?Collection;
    public function isLearned(): bool;
    public function setLearned(bool $learned): static;
}
