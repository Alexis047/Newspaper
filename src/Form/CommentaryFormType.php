<?php

namespace App\Form;

use App\Entity\Commentary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaryFormType extends AbstractType
{
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Laissez un commentaire ici',
                ],
                'constraints' => [
                    new NotBlank()
                ],
            ])
        ;

        if ($this->security->getUser()) {

            $builder
                ->add('submit', SubmitType::class, [
                    'label' => 'Commenter <i class="fa-solid fa-paper-plane"></i>',
                    'label_html' => true,
                    'validate' => false,
                    'attr' => [
                        'class' => "d-block mx-auto my-3 col-3 btn btn-warning"
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
