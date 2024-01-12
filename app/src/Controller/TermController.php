<?php
declare(strict_types=1);

namespace App\Controller;

use App\Enum\TermType;
use App\Services\TermTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/terms/{type}', requirements: ['type' => 'en|ru'])]
class TermController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TermTypeService $termType;

    public function __construct(EntityManagerInterface $entityManager, TermTypeService $typeService)
    {
        $this->entityManager = $entityManager;
        $this->termType = $typeService;
    }

    #[Route('/to/{translation}', name: 'showTerms', requirements: ['translations' => 'en|ru'])]
    public function showTerms(TermType $type, TermType $translation): Response
    {
        if ($type === $translation) {
            throw new \Exception('ERROR 103');
        }

        $repository = $this->termType->getRepositoryByType($type);

        $terms = $repository->findAll(); // search by translation type

        return $this->render('terms.list.html.twig', [
            'terms' => $terms,
        ]);
    }

    #[Route('/to/{translation}/{id}', name: 'showTerm')]
    public function showTerm(TermType $type, TermType $translation, int $id): Response
    {
        $repository = $this->termType->getRepositoryByType($type);

        $term = $repository->find($id);

        if (!$term) {
            throw new NotFoundHttpException();
        }

        $termTranslations = $term->getTranslations($translation);

        return $this->render('terms.show.html.twig', [
            'term' => $term,
            'translations' => $termTranslations,
        ]);
    }

    #[Route('/to/{translation}/add', name: 'addTerm', methods: 'GET', priority: 2)]
    public function showAddForm(): Response
    {
        return $this->render('terms.add.html.twig');
    }

    #[Route('/to/{translation}/add', methods: 'POST', priority: 2)]
    public function saveTerm(
        TermType $type,
        TermType $translationType,
        Request $request
    ): Response
    {
        $entityClass = $this->termType->getEntityClassByType($type);
        $newTerm = new $entityClass();

        //validation on exist

        $newTerm->setTerm($request->get('term'));
        $newTerm->setLearned(!!$request->get('learned'));

        $repository = $this->termType->getRepositoryByType($translationType);

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $repository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationEntityClass = $this->termType->getEntityClassByType($translationType);

                $translationTerm = new $translationEntityClass();
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $newTerm->addRussianTranslation($translationTerm);
        }

        $this->entityManager->persist($newTerm);
        $this->entityManager->flush();

        return $this->redirectToRoute('addTerm', ['type' => $type->value, 'translation' => $translationType->value]);
    }

    #[Route('/{id}/delete', name: 'removeTerm', methods: 'POST')]
    public function removeTerm(TermType $type, int $id): Response
    {
        $repository = $this->termType->getRepositoryByType($type);

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($term);
        $this->entityManager->flush();

        return $this->redirectToRoute('showTerms', [
            'type' => $type->value,
            'translation' => 'ru', // to change
        ]);
    }

    #[Route('/{id}/remove-translation/{translationType}/{translationId}', name: 'removeTranslation', methods: 'POST')]
    public function removeTranslation(TermType $type, int $id, TermType $translationType, int $translationId): Response
    {
        if ($type === $translationType) {
            throw new \Exception('ERROR 104');
        }

        $repository = $this->termType->getRepositoryByType($type);

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $translationRepository = $this->termType->getRepositoryByType($translationType);
        $translationTerm = $translationRepository->find($translationId);

        if ($translationType === TermType::EN) {
            $term->removeEnglishTranslation($translationTerm);
        } else {
            $term->removeRussianTranslation($translationTerm);
        }

        $this->entityManager->flush($term);

        return $this->redirectToRoute('showTerm', [
            'type' => $type->value,
            'id' => $id,
        ]);
    }

    #[Route('/{id}/add-translations/{translationType}', name: 'AddTranslations', methods: 'POST')]
    public function addTranslation(TermType $type, int $id, TermType $translationType, Request $request): Response
    {
        if ($type === $translationType) {
            throw new \Exception('ERROR 104');
        }

        $repository = $this->termType->getRepositoryByType($type);
        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $translationRepository = $this->termType->getRepositoryByType($translationType);

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $translationRepository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationEntityClass = $this->termType->getEntityClassByType($translationType);

                $translationTerm = new $translationEntityClass();
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $term->addRussianTranslation($translationTerm);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('showTerm', [
            'type' => $type->value,
            'id' => $id,
        ]);
    }
}
