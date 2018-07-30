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

class AddCenter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('name', TextType::class, [

                'required'  => true,
                'label'     => 'Name',
                'attr'      => [

                    'placeholder' => 'Name :'

                ]
            ])

            ->add('city', TextType::class, [

                'required'  => true,
                'label'     => 'Ville',
                'attr'      => [

                    'placeholder' => 'Ville :'

                ]
            ])

            ->add('code', TextType::class, [

                'required'  => true,
                'label'     => 'Code',
                'attr'      => [

                    'placeholder' => 'Code :'

                ]
            ])


            ->add('submit', SubmitType::class, [

                'label' => 'Créer le centre'

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