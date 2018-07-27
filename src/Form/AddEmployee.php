<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/07/2018
 * Time: 12:37
 */

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

class AddEmployee extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('username', TextType::class, [

                'required'  => true,
                'label'     => 'Name',
                'attr'      => [

                    'placeholder' => 'Name :'

                ]

            ])


            ->add('password',PasswordType::class,[
                'label'=>"Password",
                'required'=>true,
            ])


            ->add('center_id', EntityType::class, array(
                // looks for choices from this entity
                'class' => Center::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Nom du centre',

            ))
            ->add('submit', SubmitType::class, [

                'label' => 'Créer l\'employe'

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