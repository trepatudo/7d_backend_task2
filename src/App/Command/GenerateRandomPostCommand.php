<?php

namespace App\Command;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Post\PostManager;
use Exception;
use joshtronic\LoremIpsum;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomPostCommand extends Command
{
    protected static $defaultName        = 'app:generate-random-post';
    protected static $defaultDescription = 'Generate a random post';

    private LoremIpsum             $loremIpsum;

    private string      $dateFormat;
    private PostManager $postManager;

    public function __construct(
        string $name = null,
        string $dateFormat,
        PostManager $postManager,
        LoremIpsum $loremIpsum
    ) {
        $this->loremIpsum = $loremIpsum;
        $this->dateFormat = $dateFormat;
        $this->postManager = $postManager;

        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addOption('summary', 's', InputOption::VALUE_NONE,
                'Generate the summary of the day instead of fully random');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // We could turn this into inheritance for a post generator and make it extensible, I don't think for a single option (Summary) is needed
        // If we would have --type=summary or --type=random then I would have created a specific factory for this type of posts
        $isSummaryPost = $input->getOption('summary');
        [$title, $content] = $isSummaryPost ? $this->getSummaryData() : $this->getRandomPostData();


        try {
            $this->postManager->addPost($title, $content);
            $output->writeln(sprintf('A %s post has been generated.', $isSummaryPost ? 'summary' : 'random'));
        } catch (Exception $e) {
            $output->writeln('<error>An error occurred while trying to generate post</error>');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function getRandomPostData(): array
    {
        $title   = $this->loremIpsum->words(mt_rand(4, 6));
        $content = $this->loremIpsum->paragraphs(2);
        return [$title, $content];
    }

    private function getSummaryData(): array
    {
        $title = "Summary ";
        $title .= date($this->dateFormat);

        $content = $this->loremIpsum->paragraphs(1);
        return [$title, $content];
    }
}
