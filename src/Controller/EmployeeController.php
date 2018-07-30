<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 30/07/2018
 * Time: 11:02
 */

namespace App\Controller;


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

}