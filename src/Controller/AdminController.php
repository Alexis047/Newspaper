<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        $categories = $entityManager->getRepository(Category::class)->findBy(['deletedAt' => null]);
        $users = $entityManager->getRepository(User::class)->findAll();
        return $this->render('admin/show_dashboard.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'users' => $users
        ]);
    } // end function show()
} // end class
