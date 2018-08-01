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

use App\Entity\Card;
use App\Entity\Center;
use App\Entity\Customer;
use App\Form\AttachCard;
use App\Form\ForgotPassword;
use App\Form\ResetPassword;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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


    /**
     * @Route("/espace_client", name="espace_client", methods={"GET", "POST"})
     */
    public function espace_client()
    {
        return $this->render('espace_client.html.twig');

    }



    /**
     * @Route("/customer_attach_card", name="customer_attach_card", methods={"GET", "POST"})
     */
    public function attach_card(EntityManagerInterface $em,Request $request)
    {
        $customer = $this->get('security.token_storage')->getToken()->getUser();

        if ($customer->getCard() !== null)
        {
            return $this->render('espace_client.html.twig', [
                'success' => 'Vous possédez déja une carte !',
            ]);
        }
        else
            {
            $form = $this->createForm(AttachCard::class);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                //$test = $request->get('add_employee');
                $test = $form->getData();

                $card_number = $test['card_number'];


                $card = $this->getDoctrine()
                    ->getRepository(Card::class)
                    ->findByCode($card_number);

                $customer = $this->getDoctrine()->getRepository(Customer::class)->find($this->get('security.token_storage')->getToken()->getUser()->getId());

                if ($card !== null) {
                    // $card->setCustomer($employee = $this->get('security.token_storage')->getToken()->getUser()->getId());

                    $card->setCustomer($this->get('security.token_storage')->getToken()->getUser());
                    $customer->setCard($card);

                    $em->persist($customer);
                    $em->persist($card);
                    $em->flush();

                    return $this->render('espace_client.html.twig', [
                        'success' => 'Carte correctement rattachée',
                    ]);
                } else {
                    return $this->render('customer_attach_card.html.twig', [
                        'success' => 'Impossible de rattacher cette carte (Elle n\'existe pas)',
                        'form' => $form->createView()
                    ]);
                }

            }

            return $this->render('customer_attach_card.html.twig', [
                'form' => $form->createView(),
            ]);

        }
    }


    /**
     * @Route("/lost_card", name="customer_lost_card", methods={"GET", "POST"})
     */
    public function lostCard(EntityManagerInterface $em)
    {
        return $this->render('lost_card.html.twig');
    }

    /**
     * @Route("/confirm_lost_card", name="customer_confirm_lost_card", methods={"GET", "POST"})
     */
    public function confirmlostCard(EntityManagerInterface $em)
    {
        $customer = $this->get('security.token_storage')->getToken()->getUser();
        $card = $customer->getCard();
        if ($card == null)
        {
            return $this->render('lost_card.html.twig', [
                'success' => 'Vous ne possédez pas de carte !',
            ]);
        }
        else
        {
            $customer->setCard(null);
            $card->setCustomer(null);

            $em->persist($customer);
            $em->persist($card);
            $em->remove($card);
            $em->flush();

            return $this->render('lost_card.html.twig', [
                'success' => 'Carte supprimé !',
            ]);
        }
    }


    /**
     * @Route("/email_test", name="email_test", methods={"GET", "POST"})
     */
    public function index(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Hello Email'))
            ->setFrom('lasergamewf3@gmail.com')
            ->setTo('zephyrjukilo@gmail.com')
            ->setBody(
                $this->renderView(
                    'email_test.html.twig'),
                'text/html');

        $mailer->send($message);

        return $this->render('Index/index.html.twig');
    }

    /**
     * @Route("/forgot_password", name="customer_forgot_password", methods={"GET", "POST"})
     */
    public function forgotPassword(\Swift_Mailer $mailer, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ForgotPassword::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $em->getRepository(Customer::class);
            $test = $form->getData();

            $username = $test['username'];

            $customer = $repository->findOneBy( array(
                'username' => $username
            ));

            dump($customer);

            $email = $customer->getEmail();

            $message = (new \Swift_Message('Réinitialisation de votre mot de passe Lasergame WF3'))
                ->setFrom('lasergamewf3@gmail.com')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'email_reset_password.html.twig', array(
                            'param' => urlencode(base64_encode($customer->getId()))
                        )
                     ),
                    'text/html');

            $mailer->send($message);

            return $this->render('Index/index.html.twig',[
                'success' => 'Un e-mail de réinitialisation de votre mot de passe vous a été envoyé!'
            ]);


        }

        return $this->render('forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/reset_password", name="customer_reset_password", methods={"GET", "POST"})
     */
    public function resetPassword(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ResetPassword::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cryptedCustomerId = $request->get('user');
            if($cryptedCustomerId != null){
                $customerId = base64_decode($cryptedCustomerId);
                $test = $form->getData();

                $repository = $em->getRepository(Customer::class);
                $customer = $repository->find($customerId);

                $newPassword = $test['password'];
                $customer->setPassword($encoder->encodePassword($customer, $newPassword));
                //$user->setPassword($newPassword);

                $em->persist($customer);
                $em->flush();

                return $this->render('Index/index.html.twig',[
                    'success' => 'Votre mot de passe a bien été réinitialisé!'
                ]);

            }else{
                return $this->render('Index/index.html.twig');
            }


        }

        return $this->render('reset_password.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/modify_info", name="customer_modify_info", methods={"GET", "POST"})
     */
    public function modifyInfo(Request $request,Packages $packages,EntityManagerInterface $em)

    {

        $ar = $this->getUser();
        $customerrequest = CustomerRequest::createFromCustomer($ar,$packages);



        $options = [
            'etat' => 'Modifier ses informations'
        ];



        $form = $this->createForm(CustomerType::class, $customerrequest,$options)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $test = $form->getData();

            $ar->setNickname($test->getNickname());

            $ar->setUsername($test->getUsername());
            $ar->setEmail($test->getEmail());
            $ar->setCenterid($test->getCenterId()->getId());
            $ar->setPhone($test->getPhone());
            $ar->setAdress($test->getAdress());
            $ar->setBirthdate($test->getBirthdate());

            $ar->setPassword($this->encoder->encodePassword($ar, $test->getPassword()));

            $em->persist($ar);

            $em->flush();

            return $this->redirectToRoute('index');
        }


        # Affichage du Formulaire dans la vue
        return $this->render('registration.html.twig', [
            'form' => $form->createView(),
            'status' => 'MODIFICATION_INFO'
        ]);



    }

    private $encoder;    /**

 * UserFactory constructor.

 * @param UserPasswordEncoderInterface $encoder

 */

    public function __construct(UserPasswordEncoderInterface $encoder)

    {

        $this->encoder = $encoder;

    }


    }



