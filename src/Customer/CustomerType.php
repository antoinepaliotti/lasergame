<?php

/**

 * Created by PhpStorm.

 * User: Etudiant0

 * Date: 29/06/2018

 * Time: 15:10

 */
namespace App\Customer;

use App\Entity\Center;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType

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

            ->add('nickname', TextType::class, [

                'required'  => true,

                'label'     => 'Nickname',

                'attr'      => [

                    'placeholder' => 'Nickname'

                ]

            ])

            ->add('adress', TextType::class, [

                'required'  => true,

                'label'     => 'Adresse',

                'attr'      => [

                    'placeholder' => 'Adresse'

                ]
            ])

            ->add('center_id', EntityType::class, array(
                // looks for choices from this entity
                'class' => Center::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label'    => 'Nom du centre',

            ))

            ->add('phone', TextType::class, [

                'required'  => true,

                'label'     => 'Telephone :',

                'attr'      => [

                    'placeholder' => 'telephone'

                ]

            ])


            ->add('birthdate', DateType::class, [

                'required'  => true,

                'label'     => 'Date de naissance',

                'attr'      => [

                    'placeholder' => 'Date de naissance :'

                ]

            ])


            ->add('password',PasswordType::class,[
                'label'=>"Password",
                'required'=>true,
            ])

            ->add('submit',SubmitType::class,[
                'label' => 'S\'inscrire'])
            ;



    }    public function configureOptions(OptionsResolver $resolver)

    {

        $resolver->setDefaults([

            //'data_class' => Article::class

            'data_class' => CustomerRequest::class,


        ]);

    }

}