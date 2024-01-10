<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\TermEN;
use App\Entity\TermRU;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class TermController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/terms/{type}/{id}', name: 'showTerm')]
    public function showTerm(string $type, int $id): Response
    {
        if ($type === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $term = $repository->find($id);

        if (!$term) {
            throw new NotFoundHttpException();
        }

        return $this->render('terms.show.html.twig', [
            'term' => $term,
        ]);
    }

    #[Route('/terms/{type}/to/{translation}', name: 'showTerms', requirements: ['type' => 'en|ru', 'translations' => 'en|ru'])]
    public function showTerms(string $type, string $translation): Response
    {
        if ($type === $translation) {
            throw new \Exception('ERROR 103');
        }

        if ($type === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $terms = $repository->findAll(); // search by translation type

        return $this->render('terms.list.html.twig', [
            'terms' => $terms,
        ]);
    }

    #[Route('/terms/{type}/add', name: 'addTerm', methods: 'GET', priority: 2)]
    public function showAddForm(): Response
    {
        return $this->render('terms.add.html.twig');
    }

    #[Route('/terms/{type}/add', methods: 'POST', priority: 2)]
    public function saveTerm(
        string $type,
        Request $request
    ): Response
    {

//        var_dump($request->get('term'));
//        var_dump($request->get('translations'));
//        var_dump($request->get('learned'));
//        die();

        if ($type === 'en') {
            $newTerm = new TermEN();
        } else {
            $newTerm = new TermRU();
        }
        //validation on exist

        $newTerm->setTerm($request->get('term'));
        $newTerm->setLearned(!!$request->get('learned'));

        $translationToType = $request->get('translationTo');

        if ($translationToType === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $repository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationTerm = new TermRU();
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $newTerm->addRussianTranslation($translationTerm);
        }

        $this->entityManager->persist($newTerm);
        $this->entityManager->flush();


//        $entityManager = $this->registry->getManagerForClass(TermEN::class);

//        $newTerm = new TermEN();
//        $newTerm->setTerm($term);
////        $newTerm->setTranslation($translation);
//
//        $this->entityManager->persist($newTerm);
//        $this->entityManager->flush();

        return $this->redirectToRoute('addTerm', ['type' => $type]);
    }

    #[Route('/terms/{type}/{id}/delete', name: 'removeTerm', methods: 'POST')]
    public function removeTerm(string $type, int $id): Response
    {
        if ($type === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($term);
        $this->entityManager->flush();

        return $this->redirectToRoute('showTerms', [
            'type' => $type,
            'translation' => 'ru', // to change
        ]);
    }

    #[Route('/terms/{type}/{id}/remove-translation/{translationType}/{translationId}', name: 'removeTranslation', methods: 'POST')]
    public function removeTranslation(string $type, int $id, string $translationType, int $translationId): Response
    {
        if ($type === $translationType) {
            throw new \Exception('ERROR 104');
        }

        if ($type === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        if ($translationType === 'en') {
            $translationRepository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $translationRepository = $this->entityManager->getRepository(TermRU::class);
        }

        $translationTerm = $translationRepository->find($translationId);

        if ($translationType === 'en') {
            $term->removeEnglishTranslation($translationTerm);
        } else {
            $term->removeRussianTranslation($translationTerm);
        }

        $this->entityManager->flush($term);

        return $this->redirectToRoute('showTerm', [
            'type' => $type,
            'id' => $id,
        ]);
    }

    #[Route('/terms/{type}/{id}/add-translations/{translationType}', name: 'AddTranslations', methods: 'POST')]
    public function addTranslation(string $type, int $id, string $translationType, Request $request): Response
    {
        if ($type === $translationType) {
            throw new \Exception('ERROR 104');
        }

        if ($type === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $term = $repository->find($id);

        if ($term === null) {
            throw new NotFoundHttpException();
        }

        if ($translationType === 'en') {
            $translationRepository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $translationRepository = $this->entityManager->getRepository(TermRU::class);
        }

        foreach ($request->get('translations') as $translation) {
            if (!$translation) {
                continue;
            }

            $translationTerm = $translationRepository->findOneBy(['term' => $translation]);

            if ($translationTerm === null) {
                $translationTerm = new TermRU(); // HARDCODED
                $translationTerm->setTerm($translation);

                $this->entityManager->persist($translationTerm);
            }

            $term->addRussianTranslation($translationTerm);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('showTerm', [
            'type' => $type,
            'id' => $id,
        ]);
    }
}
