<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Repository\UserRepository;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/profile')]
class UserController extends AbstractController
{
    #[Route('/voir-mon-compte', name: 'show_profile', methods: ['GET'])]
    public function showProfile(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findBy(['author' => $this->getUser()]);

        return $this->render('user/show_profile.html.twig', [
            'articles' => $articles
        ]);
    } // end function showProfile


    #[Route('/changer-mon-mot-de-passe', name: 'change_password', methods: ['GET', 'POST'])]
    public function changePassword(Request $request, UserRepository $repository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(ChangePasswordFormType::class)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            # Récupération en BDD du $user, cela nous permet d'utiliser les méthodes de notre entité User (ex: setUpdatedAt())
            $user = $repository->find($this->getUser());

            // Listing étapes pour vérifier le password :
            # 1 - Un nouvel input
            # 2 - Récupération de la valeur de l'input
            # 3 - Hasher le currentPassword pour comparaison avec celui en BDD
            # 4 - Condition de vérification
            # 5 - Si la condition est vérifié, alors on exécute la suite.

            /* *********** VERIFICATION DU MDP **************** */

            $currentPassword = $form->get('currentPassword')->getData();

            if(!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('warning', "Le mot de passe actuel n'est pas valide");
                return $this->redirectToRoute('show_profile');
            }
            
            $user->setUpdatedAt(new DateTime());

            // Variabilisation de la valeur de l'input 'plainPassword' de notre formulaire
            $plainPassword = $form->get('plainPassword')->getData();

            $user->setPassword($passwordHasher->hashPassword(
                $user, $plainPassword
            ));
            // dd($plainPassword);

            $repository->add($user, true);

            $this->addFlash('success', "Votre mot de passe a bien été changé.");
            return $this->redirectToRoute('show_profile');
        } // end if($form)

        return $this->render('security/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    } // end function changePassword
}
