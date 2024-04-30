<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Domain\Post\PostManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="app_post_index")
     */
    public function index(PostRepository $postRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $posts = $postRepository->findAllQuery();

        $pagination = $paginator->paginate(
            $posts, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            100 /*limit per page*/
        );

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/post/{id}", name="app_post_show")
     */
    public function show(PostManager $postManager, $id): Response
    {
        return $this->render('post/show.html.twig', [
            'post' => $postManager->findPost($id),
        ]);
    }
}
