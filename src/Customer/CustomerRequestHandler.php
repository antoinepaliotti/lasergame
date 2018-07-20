<?php


namespace App\Customer;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Customer;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CustomerRequestHandler

{

    private $em;

    private $customerFactory;

    private $dispatcher;

    public function __construct(EntityManagerInterface $em, CustomerFactory $customerFactory, EventDispatcherInterface $dispatcher)

    {

        $this->em = $em;

        $this->customerFactory = $customerFactory;

        $this->dispatcher = $dispatcher;

    }    public function registerAsUser(CustomerRequest $request) : Customer

    {

        # On appelle notre factory pour créer notre objet User

        $user = $this->customerFactory->createFromCustomerRequest($request);        # On sauvegarde en BDD notre User

        $this->em->persist($user);

        $this->em->flush();        # On émet notre évènement

        //$this->dispatcher->dispatch(UserEvents::USER_CREATED, new UserEvent($user));        # On retourne le nouvel utlisateur

        return $user;

    }

}