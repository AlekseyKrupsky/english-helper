<?php

namespace App\Entity;

use App\Repository\TermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TermRepository::class)]
#[ORM\Index(columns: ['term'], name: 'term_en_index')]
class TermEN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $term = null;

    #[ORM\Column]
    private ?bool $learned = null;

    #[ORM\ManyToMany(targetEntity: TermRU::class, inversedBy: "englishTranslations")]
    #[JoinTable(name: 'en_ru')]
    private Collection $russianTranslations;

    public function __construct()
    {
        $this->russianTranslations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTerm(): ?string
    {
        return $this->term;
    }

    public function setTerm(string $term): static
    {
        $this->term = $term;

        return $this;
    }

    public function getRussianTranslations(): ?string
    {
        return $this->russianTranslations;
    }

    public function addRussianTranslation(string $translation): static
    {
        $this->russianTranslations->add($translation);

        return $this;
    }

    public function isLearned(): ?bool
    {
        return $this->learned;
    }

    public function setLearned(bool $learned): static
    {
        $this->learned = $learned;

        return $this;
    }
}
