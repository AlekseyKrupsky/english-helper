<?php

namespace App\Controller;

use App\Enum\Lang;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LangController extends AbstractController
{
    #[Route('/langs', name: 'all_languages')]
    public function chooseBaseLanguageAction(): Response
    {
        return $this->render('langs/base_languages.html.twig', [
            'langs' => Lang::cases(),
        ]);
    }

    #[Route('/langs/{baseLang}', name: 'secondary_languages', requirements: ['base_lang' => 'en|ru'])]
    public function showSecondaryLanguages(Lang $baseLang): Response
    {
        return $this->render('langs/secondary_languages.html.twig', [
            'secondary_langs' => Lang::casesExceptOne($baseLang),
        ]);
    }
}
