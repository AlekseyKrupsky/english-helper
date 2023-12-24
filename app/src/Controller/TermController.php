<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\TermEN;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Annotation\Route;

class TermController extends AbstractController
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    #[Route('/terms/add', name: 'addTerm', methods: 'GET')]
    public function showAddForm(): Response
    {
        return $this->render('terms.add.html.twig');
    }

    #[Route('/terms/add', methods: 'POST')]
    public function saveTerm(
        #[MapQueryParameter] string $term,
        #[MapQueryParameter] string $translation,
    ): Response
    {
        $entityManager = $this->registry->getManagerForClass(TermEN::class);

        $newTerm = new TermEN();
        $newTerm->setTerm($term);
        $newTerm->setTranslation($translation);

        $entityManager->persist($newTerm);
        $entityManager->flush();

        return $this->redirectToRoute('addTerm');
    }
}