<?php

namespace App\Command;

use App\Entity\TermEN;
use App\Entity\TermRU;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'terms:load',
    description: 'Load terms from file',
)]
class TermsLoadCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addOption('file', null,InputOption::VALUE_REQUIRED, 'Path to file with terms')
            ->addOption('type', null,InputOption::VALUE_REQUIRED, 'Lang: ru, en')
            ->addOption('known', 'k',InputOption::VALUE_NONE, 'Optional. Known terms or not. Unknown by default')
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Without saving changes');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $filePath = $input->getOption('file');
        $termType = $input->getOption('type');
        $known = $input->getOption('known');

        $terms = $this->loadArrayFromFile($filePath);
        $terms = array_unique($terms);

        if ($termType === 'en') {
            $repository = $this->entityManager->getRepository(TermEN::class);
        } else {
            $repository = $this->entityManager->getRepository(TermRU::class);
        }

        $countTotal = 0;
        $countInserted = 0;

        foreach ($terms as $term) {
            if (!$term) {
                continue;
            }

            $countTotal++;

            if ($repository->findOneBy(['term' => $term]) !== null) {
                continue;
            }

            $newTerm = new TermEN();
            $newTerm->setLearned($known);
            $newTerm->setTerm($term);

            $this->entityManager->persist($newTerm);
            $countInserted++;
        }

        if (!$input->getOption('dry-run')) {
            $this->entityManager->flush();
        }

        $io->success(sprintf('Insertion completed. Total items: %d, inserted: %d', $countTotal, $countInserted));

        return Command::SUCCESS;
    }

    function loadArrayFromFile(string $file): array {
        $content = file_get_contents($file);
        $contentClean = str_replace(PHP_EOL, ' ', $content);

        return explode(' ', $contentClean);
    }
}
