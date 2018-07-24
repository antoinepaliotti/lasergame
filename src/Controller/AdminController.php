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
use App\Form\AddCardType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    private $em;
    /**
    * @Route("/card_management", name="admin_card_management", methods={"GET", "POST"})
    * @Security("has_role('ROLE ADMIN')")
    */
    public function card_management()
    {
        return $this->render('card_management.html.twig');

    }

    /**
     * @Route("/info_card", name="admin_info_card", methods={"GET", "POST"})
     * @Security("has_role('ROLE ADMIN')")
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
     * @Route("/add_card", name="admin_add_card", methods={"GET", "POST"})
     * @Security("has_role('ROLE ADMIN')")
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

            return $this->redirectToRoute('admin_card_management');

        }


        return $this->render('add_card.html.twig', [

            'form' => $form->createView()]);

    }


}