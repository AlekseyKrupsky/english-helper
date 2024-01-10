<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\API\AddRussianTermTranslationsController;
use App\Controller\API\RemoveRussianTermTranslationsController;
use App\DTO\TranslationDTO;
use App\Enum\TermType;
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
            normalizationContext: ['groups' => 'term:ru:item']),
        new GetCollection(
            uriTemplate: '/terms/ru',
            normalizationContext: ['groups' => 'term:ru:list']),
        new Post(
            uriTemplate: '/terms/ru',
            normalizationContext: ['groups' => 'term:ru:add']),
        new Post(
            uriTemplate: '/api/terms/ru/{id}/translations/add',
            routeName: 'add_translation_ru',
            controller: AddRussianTermTranslationsController::class,
            input: TranslationDTO::class,
        ),
        new Post(
            uriTemplate: '/api/terms/ru/{id}/translations/remove',
            routeName: 'remove_translation_ru',
            controller: RemoveRussianTermTranslationsController::class,
            input: TranslationDTO::class,
        ),
        new Delete(uriTemplate: '/terms/ru/{id}')
    ],
    order: ['term' => 'ASC'],
    paginationEnabled: false,
)]
class TermRU implements TermInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['term:ru:item', 'term:ru:list'])]
    private ?int $id = null;

    private string $type = 'ru';

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['term:ru:item', 'term:ru:list', 'term:ru:add'])]
    private ?string $term = null;

    // create migration for the change
    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['term:ru:item', 'term:ru:list', 'term:ru:add'])]
    private bool $learned = false;

    #[ORM\ManyToMany(targetEntity: TermEN::class, mappedBy: "russianTranslations")]
    #[Groups(['term:ru:item'])]
    private Collection $englishTranslations;

    public function __construct()
    {
        $this->englishTranslations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->term ?: '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
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

    public function getTranslations(TermType $type): ?Collection
    {
        return new ArrayCollection();
    }

    public function addTranslation(TermType $type, TermInterface $term): static
    {
        return $this;
    }

    public function removeTranslation(TermType $type, TermInterface $term): static
    {
        return $this;
    }

    public function getEnglishTranslations(): ?Collection
    {
        return $this->englishTranslations;
    }

    public function addEnglishTranslation(TermEN $translation): static
    {
        $translation->addRussianTranslation($this);

        return $this;
    }

    public function removeEnglishTranslation(TermEN $translation): static
    {
        $translation->removeRussianTranslation($this);

        return $this;
    }

    public function isLearned(): bool
    {
        return $this->learned;
    }

    public function setLearned(bool $learned): static
    {
        $this->learned = $learned;

        return $this;
    }
}
