<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/07/2018
 * Time: 11:02
 */

namespace App\Controller;


use App\Entity\Customer;
use App\Entity\Score;
use App\Form\ModifyScore;
use Symfony\Bundle\FrameworkBundle\Controller\Controller
    ;use App\Card\CardManager;
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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class EmployeeController extends Controller
{
    /**
     * @Route("/customer_management", name="customer_management", methods={"GET", "POST"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function customer_management()
    {

        return $this->render('customer_management.html.twig');
    }

    /**
     * @Route("/employee_create_card", name="employee_create_card", methods={"GET", "POST"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function createCard(EntityManagerInterface $em)
    {
        $card = new Card();

        $cardManager = New CardManager($em);


        $employee = $this->get('security.token_storage')->getToken()->getUser();


        $centerCode = $employee->getCenter()->getCode();

        $code = $cardManager->generateCode($centerCode);

        $card->setCardNumber($code);

        $card->setCustomerNickname('TEST');

        $em->persist($card);

        $em->flush();

        return $this->render('customer_management.html.twig',[
            'success' => 'Votre carte a correctement été créée !'
        ]);

    }


    /**
     * @Route("/employee_manage_score", name="employee_manage_score", methods={"GET", "POST"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function managescore(EntityManagerInterface $em,Request $request)
    {
        $employee = $this->get('security.token_storage')->getToken()->getUser();

        $options = [
            'centerid' => $employee->getCenter()->getId()
        ];


        $form = $this->createForm(ModifyScore::class,null,$options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $em->getRepository(Center::class);

            $test = $form->getData();

            $customer = $test['customer_id'];

            $card = $customer->getCard();

            $score = new Score();

            $score->setScoreValue($test['score']);

            $score->setDate(new \DateTime());

            $score->setCard($card);

            $em->persist($score);

            $em->flush();

            return $this->render('customer_management.html.twig',
                [
                    'form' => $form->createView(),
                    'success' => 'Score correctement rajouté'
                ]);


        }

        return $this->render('employee_manage_score.html.twig',
            [
                'form' => $form->createView()
            ]);


    }

    /**
     * @Route("/employee_list_players", name="employee_list_players", methods={"GET", "POST"})
     * @Security("has_role('ROLE_EMPLOYEE')")
     */
    public function listPlayers(EntityManagerInterface $em)
    {
        $employee = $this->get('security.token_storage')->getToken()->getUser();

        $centerId = $employee->getCenter()->getId();

        $players = $em->getRepository(Customer::class)->findBy(
            array('center_id' => $centerId)
        );

        return $this->render('list_players_employee.html.twig',
            [
                'players' => $players
            ]);
    }




}