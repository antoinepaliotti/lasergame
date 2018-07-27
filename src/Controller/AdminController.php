<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/07/2018
 * Time: 10:40
 */

namespace App\Controller;

use App\Card\CardManager;
use App\Entity\Card;
use App\Entity\Center;
use App\Entity\Employee;
use App\Form\AddCardType;
use App\Form\AddEmployee;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends Controller
{
    private $em;
    /**
    * @Route("/card_management", name="admin_card_management", methods={"GET", "POST"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function card_management()
    {
        return $this->render('card_management.html.twig');

    }

    /**
     * @Route("/administration", name="admin_management", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function admin_management()
    {
        return $this->render('administration.html.twig');

    }

    /**
     * @Route("/info_card", name="admin_info_card", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function info_card()
    {
        $repository = $this->getDoctrine()
            ->getRepository(Card::class);
        $cards = $repository->findAll();

        return $this->render('card_list.html.twig',[
            'cards' => $cards
        ]);



    }

    /**
     * @Route("/employee_management", name="admin_employee_management", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function employee_management()
    {
        return $this->render('employeemanagement.html.twig');
    }


    /**
     * @Route("/employee_add", name="admin_add_employee", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function add_employee(EntityManagerInterface $em,Request $request,UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(AddEmployee::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //$test = $request->get('add_employee');
            $test = $form->getData();

            $employee = new Employee($encoder);

            $employee->setCenter($test['center_id']);

            $employee->setUsername($test['username']);

            $employee->setPassword($employee->encoder->encodePassword($employee,$test['password']));

            $employee->setRoles('ROLE_EMPLOYEE');

            $em->persist($employee);
        }

        $em->flush();

        return $this->render('add_employee.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/add_card", name="admin_add_card", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function add_card(EntityManagerInterface $em,Request $request)
    {
        $card = New Card();
        $form = $this->createForm(AddCardType::class,$card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $test = $request->get('add_card');

            //dump($test);


            for ($i = 1; $i <= $test['number']; $i++) {

                $card = new Card();

                $cardManager = New CardManager($em);

                $center = $this->getDoctrine()
                    ->getRepository(Center::class)
                    ->find($test['center_id']);


                $code = $cardManager->generateCode($center->getCode());

                $card->setCardNumber($code);

                $card->setCustomerNickname('TEST');

                $em->persist($card);


            }
            $em->flush();


            return $this->render('card_management.html.twig',[
                'add_card' => 'Cartes correctement ajoutées'
            ]);

        }


        return $this->render('add_card.html.twig', [

            'form' => $form->createView()]);

    }


}