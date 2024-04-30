<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateRandomPostCommand extends Command
{
    protected static $defaultName = 'app:generate-random-post';
    protected static $defaultDescription = 'Generate a random post';

    private EntityManagerInterface $em;
    private LoremIpsum $loremIpsum;

    public function __construct(EntityManagerInterface $em, LoremIpsum $loremIpsum, string $name = null)
    {
        parent::__construct($name);
        $this->em = $em;
        $this->loremIpsum = $loremIpsum;
    }

    protected function configure(): void
    {
        $this
            ->addOption('summary', 's', InputOption::VALUE_NONE, 'Generate the summary of the day instead of fully random');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // We could turn this into inheritance for a post generator and make it extensible, I don't think for a single option (Summary) is needed
        // If we would have --type=summary or --type=random then I would have created a specific factory for this type of posts
        $isSummaryPost = $input->getOption('summary');
        [$title, $content] = $isSummaryPost ? $this->getSummaryData() : $this->getRandomPostData();

        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);

        $this->em->persist($post);
        $this->em->flush();

        $output->writeln(sprintf('A %s post has been generated.', $isSummaryPost ? 'summary' : 'random'));

        return Command::SUCCESS;
    }

    private function getRandomPostData(): array {
        $title = $this->loremIpsum->words(mt_rand(4, 6));
        $content = $this->loremIpsum->paragraphs(2);
        return [$title, $content];
    }
    private function getSummaryData(): array {
        $title = "Summary ";
        $title .= date("YYYY-MM-DD");

        $content = $this->loremIpsum->paragraphs(1);
        return [$title, $content];
    }
}
