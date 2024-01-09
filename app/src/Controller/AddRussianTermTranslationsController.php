<?php

namespace App\Controller;

use App\DTO\TranslationDTO;
use App\Entity\TermRU;
use App\Services\TranslationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class AddRussianTermTranslationsController extends AbstractController
{
    private TranslationService $translationService;

    public function __construct(TranslationService $translationService)
    {
        $this->translationService = $translationService;
    }

    #[Route(
        path: '/api/terms/ru/{id}/translations/add',
        name: 'add_translation_ru',
        defaults: [
            '_api_resource_class' => TermRU::class,
            '_api_operation_name' => 'add_translation_ru',
        ],
        methods: ['POST'],
    )]
    public function __invoke(TermRU $term, #[MapRequestPayload] TranslationDTO $dto)
    {
        $this->translationService->addTranslations($term, $dto->translations);

        return $term;
    }
}
