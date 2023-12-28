<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\TermRURepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TermRURepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/terms/ru/{id}',
            normalizationContext: ['groups' => 'term:item']),
        new GetCollection(
            uriTemplate: '/terms/ru',
            normalizationContext: ['groups' => 'term:list']),
        new Post(
            uriTemplate: '/terms/ru',
            normalizationContext: ['groups' => 'term:add']),
        new Delete(uriTemplate: '/terms/ru/{id}')
    ],
    order: ['term' => 'ASC'],
    paginationEnabled: false,
)]
class TermRU
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

    #[ORM\ManyToMany(targetEntity: TermEN::class, mappedBy: "russianTranslations")]
    #[Groups(['term:item'])]
    private Collection $englishTranslations;

    public function __construct()
    {
        $this->englishTranslations = new ArrayCollection();
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

    public function getRussianTranslations(): ?Collection
    {
        return $this->englishTranslations;
    }

    public function addRussianTranslation(string $translation): static
    {
        $this->englishTranslations->add($translation);

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
