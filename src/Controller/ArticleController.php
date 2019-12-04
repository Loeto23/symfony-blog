<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 *
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
//    /**
//     * @Route("/{id}, requirements={"id": "\d+"})
//     */
//    public function index(ArticleRepository $repository, Article $article)
//    {
//        $unArticle = $repository->findBy(['id' => $article]);
//
//        return $this->render('article/index.html.twig',
//            [
//                'unArticle' => $unArticle
//            ]
//        );
//    }

    /**
     * @Route("/{id}", requirements={"id": "\d+"})
     */
    public function index(Article $article)
    {

        return $this->render('article/index.html.twig',
            [
                'article' => $article
            ]
        );
    }
}
