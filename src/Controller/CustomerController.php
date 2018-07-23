<?php

/**

 * Created by PhpStorm.

 * User: Etudiant0

 * Date: 29/06/2018

 * Time: 14:55

 */

namespace App\Controller;

use App\Customer\CustomerRequest;
use App\Customer\CustomerRequestHandler;
use App\Customer\CustomerType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{

    /**

     * Formulaire pour créer un utilisateur

     * @Route("/inscription", name="user_register", methods={"GET", "POST"})

     * @param Request $request

     * @param CustomerRequestHandler $customerRequestHandler

     * @return Response

     */

    public function register(Request $request, CustomerRequestHandler $customerRequestHandler)

    {

        # Création d'un nouvel user

        //$article = new Article();

        $customer = new CustomerRequest();        # Créer un Formulaire permettant l'ajout d'un User

        $form =$this->createForm(CustomerType::class, $customer);        # Traitement des données POST

        $form->handleRequest($request);        # Vérification et traitement du formulaire

        if ($form->isSubmitted() && $form->isValid()) {


           $customerRequestHandler->registerAsUser($customer);        # Flash Messages

           //$this->addFlash('notice', 'Félicitations, vous êtes maintenant inscrit !');            # Redirection sur l'accueil

           return $this->redirectToRoute('security_login');

       }        # Affichage du formulaire dans la vue

        return $this->render('registration.html.twig', [

            'form' => $form->createView() ]);

    }

}