<?php
namespace App\Customer;

use App\Entity\Customer;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerFactory

{

    private $encoder;    /**

     * UserFactory constructor.

     * @param UserPasswordEncoderInterface $encoder

     */

    public function __construct(UserPasswordEncoderInterface $encoder)

    {

        $this->encoder = $encoder;

    }
    /**

     * @param CustomerRequest $request

     * @return Customer

     */

    public function createFromCustomerRequest(CustomerRequest $request) : Customer

    {

        $customer = new Customer();

        $customer->setUsername($request->getUsername());

        $customer->setNickname($request->getNickname());

        $customer->setBirthdate($request->getBirthdate());

        $customer->setAdress($request->getAdress());

        $customer->setPhone($request->getPhone());

        $customer->setEmail($request->getEmail());

        $customer->setRoles($request->getRoles());

        $customer->setCenterId($request->getCenterId()->getId());

        $customer->setPassword($this->encoder->encodePassword($customer, $request->getPassword()));

        return $customer;

    }

}
