<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 20/07/2018
 * Time: 13:33
 */

namespace App\Controller;


use App\Card\CardManager;
use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @Route("/index", name="index", methods={"GET", "POST"})
     */
    public function index()
    {
        /**
         *

        $repository = $this->getDoctrine()->getRepository(Customer::class);

        $customers = $repository->findAll();

        # var_dump($articles);
        #  return new Response("<html><body><h1>Page d'acceuil</h1></body></html>");

         *
         * */



        return $this->render('index/index.html.twig');
    }

    /**
     * @Route("/test", name="test", methods={"GET", "POST"})
     */
    public function testcode(EntityManagerInterface $em)
    {
        $cardmanager = new CardManager($em);

        $finalcode = $cardmanager->testgenerateCode(1113744137);

        return $this->render('registration.html.twig',[
            'code' => $finalcode
        ]);



    }

}