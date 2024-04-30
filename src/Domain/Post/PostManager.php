<?php

namespace Domain\Post;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

class PostManager
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function addPost(string $title, string $content)
    {
        $post = new Post();
        $post->setTitle($title);
        $post->setContent($content);

        $this->postRepository->add($post, true);
    }

    public function findPost($id): Post
    {
        return $this->postRepository->find($id);
    }
}
