<?php

namespace App\Entity;

use App\Enum\Lang;
use Doctrine\Common\Collections\Collection;

interface TermInterface
{
    public function getTerm(): ?string;
    public function getLang(): Lang;
    public function addTranslation(Lang $lang, TermInterface $term): static;
    public function removeTranslation(Lang $lang, TermInterface $term): static;
    public function getTranslations(Lang|string $lang): ?Collection;
    public function isLearned(): bool;
    public function setLearned(bool $learned): static;
}
