<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\Lang;
use App\Services\LangService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/langs/{baseLang}/{secondaryLang}/terms', requirements: ['baseLang' => 'en|ru', 'secondaryLang' => 'en|ru'])]
class TermController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LangService $langService;

    public function __construct(EntityManagerInterface $entityManager, LangService $langService)
    {
        $this->entityManager = $entityManager;
        $this->langService = $langService;
    }

    #[Route('/', name: 'showTerms')]
    public function showTerms(Lang $baseLang, Lang $secondaryLang): Response
    {
        if ($baseLang === $secondaryLang) {
            throw new \Exception('ERROR 103');
        }

        $repository = $this->langService->getRepositoryByType($baseLang);

        $terms = $repository->findAll();

        return $this->render('terms.list.html.twig', [
            'terms' => $terms,
        ]);
    }

    #[Route('/{id}', name: 'showTerm')]
    public function showTerm(Lang $baseLang, Lang $secondaryLang, int $id): Response
    {
        $repository = $this->langService->getRepositoryByType($baseLang);

        $term = $repository->find($id);

        if (!$term) {
            throw new NotFoundHttpException();
        }

        $termTranslations = $term->getTranslations($secondaryLang);

        return $this->render('terms.show.html.twig', [
            'term' => $term,
            'translations' => $termTranslations,
        ]);
    }

    #[Route('/add', name: 'addTerm', methods: 'GET', priority: 2)]
    public function showAddForm(): Response
    {
        return $this->render('terms.add.html.twig');
    }

    #[Route('/add', methods: 'POST', priority: 2)]
    public function saveTerm(
        Lang    $baseLang,
        Lang    $secondaryLang,
        Request $request
    ): Response
    {
        $entityClass = $this->langService->getEntityClassByType($baseLang);
        $newTerm = new $entityClass();

        //validation on exist

        $newTerm->setTerm($request->get('term'));
        $newTerm->setLearned(!!$request->get('learned'));

        $repository = $this->langService->getRepositoryByType($secondaryLang);

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $repository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationEntityClass = $this->langService->getEntityClassByType($secondaryLang);

                $translationTerm = new $translationEntityClass();
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $newTerm->addRussianTranslation($translationTerm);
        }

        $this->entityManager->persist($newTerm);
        $this->entityManager->flush();

        return $this->redirectToRoute('addTerm', ['baseLang' => $baseLang->value, 'secondaryLang' => $secondaryLang->value]);
    }

    #[Route('/{id}/delete', name: 'removeTerm', methods: 'POST')]
    public function removeTerm(Lang $baseLang, Lang $secondaryLang, int $id): Response
    {
        $repository = $this->langService->getRepositoryByType($baseLang);

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($term);
        $this->entityManager->flush();

        return $this->redirectToRoute('showTerms', [
            'baseLang' => $baseLang->value,
            'secondaryLang' => $secondaryLang->value,
        ]);
    }

    #[Route('/{id}/remove-translation/{translationId}', name: 'removeTranslation', methods: 'POST')]
    public function removeTranslation(Lang $baseLang, int $id, Lang $secondaryLang, int $translationId): Response
    {
        if ($baseLang === $secondaryLang) {
            throw new \Exception('ERROR 104');
        }

        $repository = $this->langService->getRepositoryByType($baseLang);

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $translationRepository = $this->langService->getRepositoryByType($secondaryLang);
        $translationTerm = $translationRepository->find($translationId);

        // to change entity method
        if ($secondaryLang === Lang::EN) {
            $term->removeEnglishTranslation($translationTerm);
        } else {
            $term->removeRussianTranslation($translationTerm);
        }

        $this->entityManager->flush($term);

        return $this->redirectToRoute('showTerm', [
            'baseLang' => $baseLang->value,
            'id' => $id,
        ]);
    }

    #[Route('/{id}/add-translations', name: 'AddTranslations', methods: 'POST')]
    public function addTranslation(Lang $baseLang, int $id, Lang $secondaryLang, Request $request): Response
    {
        if ($baseLang === $secondaryLang) {
            throw new \Exception('ERROR 104');
        }

        $repository = $this->langService->getRepositoryByType($baseLang);
        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $translationRepository = $this->langService->getRepositoryByType($secondaryLang);

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $translationRepository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationEntityClass = $this->langService->getEntityClassByType($secondaryLang);

                $translationTerm = new $translationEntityClass();
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $term->addRussianTranslation($translationTerm);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('showTerm', [
            'baseLang' => $baseLang->value,
            'id' => $id,
        ]);
    }
}
