<?php

namespace App\Controller;

use DateTime;
use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin')]
class CategoryController extends AbstractController
{
    #[Route('/creer-une-categorie', name: 'create_category', methods: ['GET', 'POST'])]
    public function createCategory(Request $request, SluggerInterface $slugger, CategoryRepository $repository): Response
    {
        $category = new Category;

        $form = $this->createForm(CategoryFormType::class, $category)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $category->setCreatedAt(new DateTime());
            $category->setUpdatedAt(new DateTime());

            // L'alias nous servira à construire une URL.
            // Pour cela on utilise le $slugger
            $category->setAlias($slugger->slug($category->getName()));

            $repository->add($category, true);

            $this->addFlash('success', 'La nouvelle catégorie a bien été créée !');
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/form/category.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
