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
use App\Form\AddCenter;
use App\Form\AddEmployee;
use App\Form\DeleteCenter;
use App\Form\ModifyCenter;
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

            $employee->setRoles(array('ROLE_EMPLOYEE'));

            $em->persist($employee);

            $em->flush();

            return $this->render('employeemanagement.html.twig',[
                'success' => 'Employé créé'
            ]);


        }


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

        $form = $this->createForm(AddCardType::class);

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


    /**
     * @Route("/center_management", name="admin_center_management", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function center_management()
    {
        return $this->render('centermanagement.html.twig');
    }

    /**
     * @Route("/center_add", name="admin_add_center", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function add_center(EntityManagerInterface $em,Request $request)
    {
        $form = $this->createForm(AddCenter::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Center::class);

            //$test = $request->get('add_employee');
            $test = $form->getData();

            $center = new Center();

            $center->setName($test['name']);

            $center->setCity($test['city']);

            $center->setCode($test['code']);

            // On cherche si le code existe en BDD
            $code = $repository->findByCode($test['code']);

            //Si le code n'existe pas en BDD
            if ($code === null) {
                $em->persist($center);
            }

            $em->flush();

            return $this->render('centermanagement.html.twig',[
                'succes' => 'Votre centre à correctement été ajouté !'
            ]);

        }



        return $this->render('add_center.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/center_delete", name="admin_delete_center", methods={"GET","POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function delete_center(EntityManagerInterface $em,Request $request)
    {
        $form = $this->createForm(DeleteCenter::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$test = $request->get('add_employee');
            $test = $form->getData();

            $center = $this->getDoctrine()
                ->getRepository(Center::class)
                ->find($test['center_id']);

            $centerCode = $center->getCode();

            //$repository = $em->getRepository(Card::class);

            $employees = $em->getRepository(Employee::class)->findBy(
              array('center' => $center)
            );

            $centerCards = $em->getRepository(Card::class)->findByCenterCode($centerCode);

            foreach($centerCards as $centerCard){
                $customer = $centerCard->getCustomer();
                if($customer != null) {
                    $customer->setCenterId(0);
                    $customer->setCard(null);
                    $em->persist($customer);
                }

                $em->remove($centerCard);
            }

            //dump($centerCards);

            $em->remove($center);

            foreach($employees as $employee){
                $em->remove($employee);
            }

            $em->flush();

            return $this->render('centermanagement.html.twig',[
                'succes' => 'Votre centre à correctement été supprimé !',
                'centerCards' => $centerCards
            ]);
        }



        return $this->render('delete_center.html.twig',[
            'form' => $form->createView()
        ]);


    }


    /**
     * @Route("/center_modify", name="admin_modify_center", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function modify_center(EntityManagerInterface $em,Request $request)
    {
        $form = $this->createForm(ModifyCenter::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Center::class);
            $test = $form->getData();

            $center = $repository->find($test['center_id']->getId());

            $center->setName($test['name']);

            $center->setCity($test['city']);

            $center->setCode($test['code']);

            $em->persist($center);

            $em->flush();

            return $this->render('centermanagement.html.twig',[
                'succes' => 'Votre centre à correctement été modifié !'
            ]);


        }



        return $this->render('add_center.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/employee_list", name="admin_list_employee", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listEmployee(EntityManagerInterface $em)
    {
        $employees = $em->getRepository(Employee::class)->findAll();

        return $this->render('list_employee.html.twig',[
           'employees' => $employees
        ]);

    }

    /**
     * @Route("/center_list", name="admin_list_center", methods={"GET", "POST"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function listCenter(EntityManagerInterface $em)
    {
        $centers = $em->getRepository(Center::class)->findAll();

        return $this->render('list_center.html.twig', [
           'centers' => $centers
        ]);
    }









}