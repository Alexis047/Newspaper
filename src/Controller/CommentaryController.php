<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentary;
use App\Form\CommentaryFormType;
use App\Repository\CommentaryRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaryController extends AbstractController
{
    #[Route('/ajouter-un-commentaire/{id}', name: 'add_commentary', methods: ['GET', 'POST'])]
    public function addCommentary(Article $article, Request $request, CommentaryRepository $repository): Response
    {
        $commentary = new Commentary();

        $form = $this->createForm(CommentaryFormType::class, $commentary)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $commentary->setCreatedAt(new DateTime());
            $commentary->setUpdatedAt(new DateTime());

            $commentary->setArticle($article);
            $commentary->setAuthor($this->getUser());

            $repository->add($commentary, true);

            $this->addFlash('success', "Vous avez commenté l'article <strong>" . $article->getTitle() . "</strong> avec succès !");

            return $this->redirectToRoute('show_article', [
                'cat_alias' => $article->getCategory()->getAlias(),
                'art_alias' => $article->getAlias(),
                'id' => $article->getId()
            ]);
        }

        return $this->render('rendered/form_commentary.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
