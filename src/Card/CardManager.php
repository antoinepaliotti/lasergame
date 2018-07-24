<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 24/07/2018
 * Time: 14:23
 */

namespace App\Card;


use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

class CardManager
{

    private $em;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function generateCode($centerCode)
{

    $code = $centerCode . rand(100000, 999999);

    $sum = 0;

    $chars = str_split($code);
    foreach ($chars as $char) {
        $sum += $char;
    }

    $modulo = $sum % 9;

    $finalCode = $code . $modulo;

    $repository = $this->em->getRepository(Card::class);

    $code = $repository->findByCode($finalCode);  /// implémenter findbycode().

    if ($code !== null)
    {
        $this->generateCode($centerCode);
    }

    return $finalCode;
    }

public function testgenerateCode($centerCode)
{

    $finalCode = $centerCode;

    $repository = $this->em->getRepository(Card::class);

    $code = $repository->findByCode($finalCode);  /// implémenter findbycode().

    if ($code !== null)
    {
        $finalCode = $this->generateCode('444');
    }

    return $finalCode;
}

}