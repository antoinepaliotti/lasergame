<?php

namespace App\Form;

use App\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class ModifyCenter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('center_id', EntityType::class, array(
                // looks for choices from this entity
                'class' => Center::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Nom du centre à modifier',

            ))
            ->add('name', TextType::class, [

                'required'  => true,
                'label'     => 'Name',
                'attr'      => [

                    'placeholder' => 'Nouveau nom :'

                ]
            ])

            ->add('city', TextType::class, [

                'required'  => true,
                'label'     => 'Ville',
                'attr'      => [

                    'placeholder' => 'Nouvelle ville :'

                ]
            ])

            ->add('code', TextType::class, [

                'required'  => true,
                'label'     => 'Code',
                'attr'      => [

                    'placeholder' => 'Nouveau code :'

                ]
            ])


            ->add('submit', SubmitType::class, [

                'label' => 'Modifier le centre'

            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => null
            ]);
    }
}