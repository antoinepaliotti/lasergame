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

        $customer->setName($request->getName());

        $customer->setNickname($request->getNickname());

        $customer->setBirthdate($request->getBirthdate());

        $customer->setAdress($request->getAdress());

        $customer->setPhone($request->getPhone());

        $customer->setRoles($request->getRoles());

        $customer->setCenterId(1);

        $customer->setPassword($this->encoder->encodePassword($customer, $request->getPassword()));

        return $customer;

    }

}
