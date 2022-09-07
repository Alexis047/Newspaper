<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/voir-articles/{alias}', name: 'show_articles_from_category', methods: ['GET'])]
    public function showArticlesFromCategory(Category $category, ArticleRepository $repository): Response
    {
        $articles = $repository->findBy([
            'deletedAt' => null,
            'category' => $category->getId()
        ]);

        return $this->render('article/show_articles_from_categories.html.twig', [
            'articles' => $articles,
            'category' => $category
        ]);
    }

    // category->alias / article->alias _ article->id
    #[Route('{cat_alias}/{art_alias}_{id}', name: 'show_article', methods: ['GET'])]
    public function showArticle(Article $article): Response
    {
        return $this->render('article/show_article.html.twig', [
            'article' => $article
        ]);
    }
}
