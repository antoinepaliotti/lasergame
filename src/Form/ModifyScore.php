<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/07/2018
 * Time: 16:59
 */

namespace App\Form;


use App\Entity\Center;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class ModifyScore extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder
            ->add('customer_id', EntityType::class, array(
                // looks for choices from this entity
                'class' => Customer::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.roles = :val')
                        ->setParameter('val', 'a:1:{i:0;s:9:"ROLE_USER";}');
                },
                // uses the User.username property as the visible option string
                'choice_label' => 'username',
                'expanded' => false,
                'multiple' => false,
                'label' => 'Nom du joueur',

            ))
            ->add('score', IntegerType::class, [

                'required'  => true,
                'label'     => 'Score',
                'attr'      => [

                    'placeholder' => 'Score :'

                ]
            ])

            ->add('submit', SubmitType::class, [

                'label' => 'Modifier le score'

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