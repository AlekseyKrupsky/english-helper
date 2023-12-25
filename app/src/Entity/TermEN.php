<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\TermRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TermRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'term:item']),
        new GetCollection(normalizationContext: ['groups' => 'term:list']),
        new Post(normalizationContext: ['groups' => 'term:add']),
        new Delete()
    ],
    order: ['term' => 'ASC'],
    paginationEnabled: false,
)]
class TermEN
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['term:item', 'term:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['term:item', 'term:list', 'term:add'])]
    private ?string $term = null;

    #[ORM\Column]
    #[Groups(['term:item', 'term:list', 'term:add'])]
    private ?bool $learned = null;

    #[ORM\ManyToMany(targetEntity: TermRU::class, inversedBy: "englishTranslations")]
    #[JoinTable(name: 'en_ru')]
    #[Groups(['term:item'])]
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
