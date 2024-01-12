<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\API\AddEnglishTermTranslationsController;
use App\Controller\API\RemoveEnglishTermTranslationsController;
use App\DTO\TranslationDTO;
use App\Enum\Lang;
use App\Repository\TermENRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TermENRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/terms/en/{id}',
            normalizationContext: ['groups' => 'term:en:item']
        ),
        new GetCollection(
            uriTemplate: '/terms/en',
            normalizationContext: ['groups' => 'term:en:list']
        ),
        new Post(
            uriTemplate: '/terms/en',
            denormalizationContext: ['groups' => 'term:en:add']
        ),
        new Post(
            uriTemplate: '/api/terms/en/{id}/translations/add',
            routeName: 'add_translation_en',
            controller: AddEnglishTermTranslationsController::class,
            input: TranslationDTO::class,
        ),
        new Post(
            uriTemplate: '/api/terms/en/{id}/translations/remove',
            routeName: 'remove_translation_en',
            controller: RemoveEnglishTermTranslationsController::class,
            input: TranslationDTO::class,
        ),
        new Delete(uriTemplate: '/terms/en/{id}'),
    ],
    order: ['term' => 'ASC'],
    paginationEnabled: false,
)]
class TermEN extends AbstractTerm
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['term:en:item', 'term:en:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['term:en:item', 'term:en:list', 'term:en:add'])]
    private ?string $term = null;

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['term:en:item', 'term:en:list', 'term:en:add'])]
    private bool $learned = false;

    #[ORM\ManyToMany(targetEntity: TermRU::class, inversedBy: "englishTranslations")]
    #[ORM\JoinColumn(name: "term_en_id", referencedColumnName: "id")]
    #[ORM\InverseJoinColumn(name: "term_ru_id", referencedColumnName: "id")]
    #[Groups(['term:en:item'])]
    private Collection $russianTranslations;

    public function __construct()
    {
        $this->russianTranslations = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->term ?: '';
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

    public function addTranslation(Lang $type, TermInterface $term): static
    {
        return $this;
    }

    public function removeTranslation(Lang $type, TermInterface $term): static
    {
        return $this;
    }

    public function getRussianTranslations(): ?Collection
    {
        return $this->russianTranslations;
    }

    public function addRussianTranslation(TermRU $translation): static
    {
        if (!$this->russianTranslations->contains($translation)) {
            $this->russianTranslations->add($translation);
        }

        return $this;
    }

    public function removeRussianTranslation(TermRU $translation): static
    {
        if ($this->russianTranslations->contains($translation)) {
            $this->russianTranslations->removeElement($translation);
        }

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
