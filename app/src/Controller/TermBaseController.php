<?php

namespace App\Controller;

use App\Enum\TermType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TermBaseController extends AbstractController
{
    #[Route('/terms', name: 'all_languages')]
    public function showLanguages(): Response
    {
        return $this->render('terms.languages.html.twig', [
            'termTypes' => TermType::cases(),
        ]);
    }

    #[Route('/terms/{type}', name: 'language_translations', requirements: ['type' => 'en|ru'])]
    public function showLanguageTranslations(TermType $type): Response
    {
        return $this->render('terms.language.html.twig', [
            'termTypes' => TermType::casesExceptOne($type),
        ]);
    }
}
